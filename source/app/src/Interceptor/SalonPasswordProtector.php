<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Interceptor;

use AppCore\Domain\SalonSession\SalonPasswordProtectSessionStoreInterface;
use BEAR\Resource\NullRenderer;
use BEAR\Resource\ResourceObject;
use BEAR\Sunday\Extension\Router\RouterInterface;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Annotation\SalonPasswordLock;
use MyVendor\MyProject\Annotation\SalonPasswordProtect;
use MyVendor\MyProject\Auth\SalonAuthenticatorInterface;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;

use function assert;
use function http_build_query;

/** @SuppressWarnings(PHPMD.LongVariable) */
class SalonPasswordProtector implements MethodInterceptor
{
    public function __construct(
        private readonly SalonAuthenticatorInterface $authenticator,
        private readonly RouterInterface $router,
        private readonly SalonPasswordProtectSessionStoreInterface $passwordProtectSessionStore,
    ) {
    }

    public function invoke(MethodInvocation $invocation): mixed
    {
        $protect = $invocation->getMethod()->getAnnotation(SalonPasswordProtect::class);
        if ($protect instanceof SalonPasswordProtect) {
            return $this->protect($invocation);
        }

        $lock = $invocation->getMethod()->getAnnotation(SalonPasswordLock::class);
        if ($lock instanceof SalonPasswordLock) {
            return $this->lock($invocation);
        }

        return $invocation->proceed();
    }

    private function protect(MethodInvocation $invocation): mixed
    {
        $passwordProtectSession = $this->passwordProtectSessionStore->get();
        if ($passwordProtectSession->isUnlocking()) {
            return $invocation->proceed();
        }

        $ro = $invocation->getThis();
        assert($ro instanceof ResourceObject);

        $uri = $ro->uri;
        $path = $this->router->generate($uri->path, $uri->query);
        if ($path === false) {
            $path = $uri->path;
        }

        if ($uri->method === 'get') {
            $path .= empty($uri->query) ? '' : '?' . http_build_query($uri->query);
        }

        $passwordProtectSession = $passwordProtectSession->protect($path);
        $this->passwordProtectSessionStore->update($passwordProtectSession);

        $ro->setRenderer(new NullRenderer());
        $ro->code = StatusCode::FOUND;
        $ro->headers = ['Location' => $this->authenticator->getPasswordRedirect()];
        $ro->view = '';
        $ro->body = [];

        return $ro;
    }

    private function lock(MethodInvocation $invocation): mixed
    {
        $passwordProtectSession = $this->passwordProtectSessionStore->get();
        $passwordProtectSession = $passwordProtectSession->lock();
        $this->passwordProtectSessionStore->update($passwordProtectSession);

        return $invocation->proceed();
    }
}
