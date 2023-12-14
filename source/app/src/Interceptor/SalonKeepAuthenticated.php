<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Interceptor;

use MyVendor\MyProject\Annotation\Qualifier\Cookie;
use MyVendor\MyProject\Auth\SalonAuthenticatorInterface;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;

use function is_string;

class SalonKeepAuthenticated implements MethodInterceptor
{
    /** @param array<array-key, mixed> $cookie */
    public function __construct(
        private readonly SalonAuthenticatorInterface $authenticator,
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
