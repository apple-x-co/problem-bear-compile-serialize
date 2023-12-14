<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Interceptor;

use AppCore\Domain\CustomerSession\CustomerGenericSessionStoreInterface;
use AppCore\Domain\LoggerInterface;
use AppCore\Qualifier\StoreLogger;
use Aura\Auth\Exception\MultipleMatches as AuraMultipleMatches;
use Aura\Auth\Exception\PasswordIncorrect as AuraPasswordIncorrect;
use Aura\Auth\Exception\PasswordMissing as AuraPasswordMissing;
use Aura\Auth\Exception\UsernameMissing as AuraUsernameMissing;
use Aura\Auth\Exception\UsernameNotFound as AuraUsernameNotFound;
use BEAR\Resource\NullRenderer;
use BEAR\Resource\ResourceObject;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Annotation\CustomerLogin;
use MyVendor\MyProject\Annotation\CustomerLogout;
use MyVendor\MyProject\Auth\CustomerAuthenticatorInterface;
use MyVendor\MyProject\Auth\MultipleMatchesException;
use MyVendor\MyProject\Auth\PasswordIncorrectException;
use MyVendor\MyProject\Auth\PasswordMissingException;
use MyVendor\MyProject\Auth\UsernameMissingException;
use MyVendor\MyProject\Auth\UsernameNotFoundException;
use MyVendor\MyProject\Form\Customer\LoginInput;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;

use function assert;
use function call_user_func;

/**
 * Customer認証
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CustomerAuthentication implements MethodInterceptor
{
    public function __construct(
        private readonly CustomerAuthenticatorInterface $authenticator,
        private readonly CustomerGenericSessionStoreInterface $genericSessionStore,
        #[StoreLogger]
        private readonly LoggerInterface $logger,
    ) {
    }

    public function invoke(MethodInvocation $invocation): mixed
    {
        $login = $invocation->getMethod()->getAnnotation(CustomerLogin::class);
        if ($login instanceof CustomerLogin) {
            return $this->login($invocation, $login->onFailure);
        }

        $logout = $invocation->getMethod()->getAnnotation(CustomerLogout::class);
        if ($logout instanceof CustomerLogout) {
            return $this->logout($invocation);
        }

        return $invocation->proceed();
    }

    private function login(MethodInvocation $invocation, string $onFailure): mixed
    {
        $args = $invocation->getNamedArguments();
        $loginUser = $args['login'] ?? null;
        assert($loginUser instanceof LoginInput);

        if ($loginUser->isValid()) {
            try {
                $this->authenticator->login($loginUser->username, $loginUser->password);
                $this->logger->log('[logged] ' . $loginUser->username);
            } catch (AuraUsernameMissing $usernameMissing) {
                return call_user_func(
                    [$invocation->getThis(), $onFailure],
                    new UsernameMissingException(
                        $usernameMissing->getMessage(),
                        $usernameMissing->getCode(),
                        $usernameMissing->getPrevious(),
                    ),
                );
            } catch (AuraPasswordMissing $passwordMissing) {
                return call_user_func(
                    [$invocation->getThis(), $onFailure],
                    new PasswordMissingException(
                        $passwordMissing->getMessage(),
                        $passwordMissing->getCode(),
                        $passwordMissing->getPrevious(),
                    ),
                );
            } catch (AuraUsernameNotFound $usernameNotFound) {
                return call_user_func(
                    [$invocation->getThis(), $onFailure],
                    new UsernameNotFoundException(
                        $usernameNotFound->getMessage(),
                        $usernameNotFound->getCode(),
                        $usernameNotFound->getPrevious(),
                    ),
                );
            } catch (AuraMultipleMatches $multipleMatches) {
                return call_user_func(
                    [$invocation->getThis(), $onFailure],
                    new MultipleMatchesException(
                        $multipleMatches->getMessage(),
                        $multipleMatches->getCode(),
                        $multipleMatches->getPrevious(),
                    ),
                );
            } catch (AuraPasswordIncorrect $passwordIncorrect) {
                return call_user_func(
                    [$invocation->getThis(), $onFailure],
                    new PasswordIncorrectException(
                        $passwordIncorrect->getMessage(),
                        $passwordIncorrect->getCode(),
                        $passwordIncorrect->getPrevious(),
                    ),
                );
            }

            $genericSession = $this->genericSessionStore->get();
            $continueUrlPath = $genericSession->continueUrlPath;
            $genericSession = $genericSession->clearContinueUrlPath();
            $this->genericSessionStore->update($genericSession);

            $ro = $invocation->proceed();
            assert($ro instanceof ResourceObject);

            $ro->setRenderer(new NullRenderer());
            $ro->code = StatusCode::FOUND;
            $ro->headers = [
                'Location' => empty($continueUrlPath) ?
                    $this->authenticator->getAuthRedirect() . '?logged' :
                    $continueUrlPath,
            ];
            $ro->view = '';
            $ro->body = [];

            return $ro;
        }

        $ro = $invocation->getThis();
        assert($ro instanceof ResourceObject);

        $ro->setRenderer(new NullRenderer());
        $ro->code = StatusCode::FOUND;
        $ro->headers = ['Location' => $this->authenticator->getUnauthRedirect() . '?parameter_error'];
        $ro->view = '';
        $ro->body = [];

        return $ro;
    }

    private function logout(MethodInvocation $invocation): mixed
    {
        $this->authenticator->logout();

        $ro = $invocation->proceed();
        assert($ro instanceof ResourceObject);

        $ro->setRenderer(new NullRenderer());
        $ro->code = StatusCode::FOUND;
        $ro->headers = ['Location' => $this->authenticator->getUnauthRedirect() . '?logged_out'];
        $ro->view = '';
        $ro->body = [];

        return $ro;
    }
}
