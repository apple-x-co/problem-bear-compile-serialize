<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Interceptor;

use BEAR\Resource\NullRenderer;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Auth\CustomerAuthenticatorInterface;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;

class MeAuthGuardian implements MethodInterceptor
{
    public function __construct(
        private readonly CustomerAuthenticatorInterface $authenticator,
    ) {
    }

    public function invoke(MethodInvocation $invocation): mixed
    {
        if ($this->authenticator->isValid()) {
            return $invocation->proceed();
        }

        $ro = $invocation->getThis();
        $ro->setRenderer(new NullRenderer());
        $ro->code = StatusCode::UNAUTHORIZED;
        $ro->view = '';
        $ro->body = [];

        return $ro;
    }
}
