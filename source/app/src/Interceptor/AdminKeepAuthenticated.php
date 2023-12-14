<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Interceptor;

use MyVendor\MyProject\Annotation\Qualifier\Cookie;
use MyVendor\MyProject\Auth\AdminAuthenticatorInterface;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;

use function is_string;

class AdminKeepAuthenticated implements MethodInterceptor
{
    /** @param array<array-key, mixed> $cookie */
    public function __construct(
        private readonly AdminAuthenticatorInterface $authenticator,
        #[Cookie]
        private readonly array $cookie,
    ) {
    }

    public function invoke(MethodInvocation $invocation): mixed
    {
        if ($this->authenticator->isValid()) {
            return $invocation->proceed();
        }

        $cookieName = $this->authenticator->getRememberCookieName();
        $payload = $this->cookie[$cookieName] ?? null;
        if (is_string($payload)) {
            $this->authenticator->continueLogin($payload);
        }

        return $invocation->proceed();
    }
}
