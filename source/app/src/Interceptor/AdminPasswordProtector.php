<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Interceptor;

use AppCore\Domain\AdminSession\AdminPasswordProtectSessionStoreInterface;
use BEAR\Resource\NullRenderer;
use BEAR\Resource\ResourceObject;
use BEAR\Sunday\Extension\Router\RouterInterface;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Annotation\AdminPasswordLock;
use MyVendor\MyProject\Annotation\AdminPasswordProtect;
use MyVendor\MyProject\Auth\AdminAuthenticatorInterface;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;

use function assert;
use function http_build_query;

/** @SuppressWarnings(PHPMD.LongVariable) */
class AdminPasswordProtector implements MethodInterceptor
{
    public function __construct(
        private readonly AdminAuthenticatorInterface $authenticator,
        private readonly RouterInterface $router,
        private readonly AdminPasswordProtectSessionStoreInterface $passwordProtectSessionStore,
    ) {
    }

    public function invoke(MethodInvocation $invocation): mixed
    {
        $protect = $invocation->getMethod()->getAnnotation(AdminPasswordProtect::class);
        if ($protect instanceof AdminPasswordProtect) {
            return $this->protect($invocation);
        }

        $lock = $invocation->getMethod()->getAnnotation(AdminPasswordLock::class);
        if ($lock instanceof AdminPasswordLock) {
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
