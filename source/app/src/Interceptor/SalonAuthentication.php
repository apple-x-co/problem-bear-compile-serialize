<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Interceptor;

use AppCore\Domain\LoggerInterface;
use AppCore\Domain\SalonSession\SalonGenericSessionStoreInterface;
use AppCore\Domain\SalonSession\SalonPasswordProtectSessionStoreInterface;
use AppCore\Qualifier\SalonLogger;
use Aura\Auth\Exception\MultipleMatches as AuraMultipleMatches;
use Aura\Auth\Exception\PasswordIncorrect as AuraPasswordIncorrect;
use Aura\Auth\Exception\PasswordMissing as AuraPasswordMissing;
use Aura\Auth\Exception\UsernameMissing as AuraUsernameMissing;
use Aura\Auth\Exception\UsernameNotFound as AuraUsernameNotFound;
use BEAR\Resource\NullRenderer;
use BEAR\Resource\ResourceObject;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Annotation\SalonLogin;
use MyVendor\MyProject\Annotation\SalonLogout;
use MyVendor\MyProject\Annotation\SalonVerifyPassword;
use MyVendor\MyProject\Auth\AttemptException;
use MyVendor\MyProject\Auth\MultipleMatchesException;
use MyVendor\MyProject\Auth\PasswordIncorrectException;
use MyVendor\MyProject\Auth\PasswordMissingException;
use MyVendor\MyProject\Auth\SalonAuthenticatorInterface;
use MyVendor\MyProject\Auth\UsernameMissingException;
use MyVendor\MyProject\Auth\UsernameNotFoundException;
use MyVendor\MyProject\Form\Salon\LoginInput;
use MyVendor\MyProject\Form\Salon\PasswordInput;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;
use Throwable;

use function assert;
use function call_user_func;
use function is_string;

/**
 * Salon 認証
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class SalonAuthentication implements MethodInterceptor
{
    public function __construct(
        private readonly SalonAuthenticatorInterface $authenticator,
        #[SalonLogger]
        private readonly LoggerInterface $logger,
        private readonly SalonGenericSessionStoreInterface $genericSessionStore,
        private readonly SalonPasswordProtectSessionStoreInterface $passwordProtectSessionStore,
    ) {
    }

    public function invoke(MethodInvocation $invocation): mixed
    {
        $method = $invocation->getMethod();

        $login = $method->getAnnotation(SalonLogin::class);
        if ($login instanceof SalonLogin) {
            return $this->login($invocation, $login->onFailure);
        }

        $logout = $method->getAnnotation(SalonLogout::class);
        if ($logout instanceof SalonLogout) {
            return $this->logout($invocation);
        }

        $verifyPassword = $method->getAnnotation(SalonVerifyPassword::class);
        if ($verifyPassword instanceof SalonVerifyPassword) {
            return $this->verifyPassword($invocation, $verifyPassword->onFailure);
        }

        return $invocation->proceed();
    }

    /**
     * @SuppressWarnings(PHPMD.ElseExpression)
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    private function login(MethodInvocation $invocation, string $onFailure): mixed
    {
        $args = $invocation->getNamedArguments();
        $input = $args['login'] ?? null;
        assert($input instanceof LoginInput);

        if ($input->isValid()) {
            try {
                if ($input->remember === 'yes') {
                    $this->authenticator->rememberLogin($input->username, $input->password);
                } else {
                    $this->authenticator->login($input->username, $input->password);
                }
            } catch (Throwable $throwable) {
                $class = match ($throwable::class) {
                    AuraUsernameMissing::class => UsernameMissingException::class,
                    AuraPasswordMissing::class => PasswordMissingException::class,
                    AuraUsernameNotFound::class => UsernameNotFoundException::class,
                    AuraMultipleMatches::class => MultipleMatchesException::class,
                    AuraPasswordIncorrect::class => PasswordIncorrectException::class,
                    AttemptException::class => AttemptException::class,
                    default => null,
                };

                if ($class === null) {
                    throw $throwable;
                }

                return call_user_func(
                    [$invocation->getThis(), $onFailure],
                    new $class(
                        $throwable->getMessage(),
                        $throwable->getCode(),
                        $throwable->getPrevious(),
                    ),
                );
            }

            $this->logger->log('[logged] ' . $input->username);

            $genericSession = $this->genericSessionStore->get();
            $passwordProtectSession = $this->passwordProtectSessionStore->get();

            $continueUrlPath = $genericSession->continueUrlPath;

            $genericSession = $genericSession->clearContinueUrlPath();
            $this->genericSessionStore->update($genericSession);

            $passwordProtectSession = $passwordProtectSession->unlock();
            $this->passwordProtectSessionStore->update($passwordProtectSession);

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
        $genericSession = $this->genericSessionStore->get();
        $passwordProtectSession = $this->passwordProtectSessionStore->get();

        $genericSession = $genericSession->clearContinueUrlPath();
        $this->genericSessionStore->update($genericSession);

        $passwordProtectSession = $passwordProtectSession->lock();
        $this->passwordProtectSessionStore->update($passwordProtectSession);

        $ro = $invocation->proceed();
        assert($ro instanceof ResourceObject);

        $this->authenticator->logout();

        $ro->setRenderer(new NullRenderer());
        $ro->code = StatusCode::FOUND;
        $ro->headers = ['Location' => $this->authenticator->getUnauthRedirect() . '?logged_out'];
        $ro->view = '';
        $ro->body = [];

        return $ro;
    }

    private function verifyPassword(MethodInvocation $invocation, string $onFailure): mixed
    {
        $args = $invocation->getNamedArguments();
        $input = $args['password'] ?? null;
        assert($input instanceof PasswordInput);

        $userName = $this->authenticator->getUserName();

        if (is_string($userName)) {
            try {
                $this->authenticator->verifyPassword(
                    $userName,
                    $input->password,
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

            $passwordProtectSession = $this->passwordProtectSessionStore->get();
            $continueUrlPath = $passwordProtectSession->continueUrlPath;
            $passwordProtectSession = $passwordProtectSession->unlock();
            $this->passwordProtectSessionStore->update($passwordProtectSession);

            $ro = $invocation->proceed();
            assert($ro instanceof ResourceObject);

            $ro->setRenderer(new NullRenderer());
            $ro->code = StatusCode::FOUND;
            $ro->headers = [
                'Location' => empty($continueUrlPath) ?
                    $this->authenticator->getAuthRedirect() :
                    $continueUrlPath,
            ];
            $ro->view = '';
            $ro->body = [];

            return $ro;
        }

        return call_user_func(
            [$invocation->getThis(), $onFailure],
            new PasswordIncorrectException(),
        );
    }
}
