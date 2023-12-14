<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Interceptor;

use MyVendor\MyProject\Annotation\RequiredPermission;
use MyVendor\MyProject\Auth\AdminAuthenticatorInterface;
use MyVendor\MyProject\Auth\ForbiddenException;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;

class AdminAuthorization implements MethodInterceptor
{
    public function __construct(
        private readonly AdminAuthenticatorInterface $authenticator,
    ) {
    }

    public function invoke(MethodInvocation $invocation): mixed
    {
        $requiredPermission = $invocation->getMethod()->getAnnotation(RequiredPermission::class);
        if ($requiredPermission === null) {
            throw new ForbiddenException();
        }

        $ac = $this->authenticator->getAccessControl();
        if (! $ac->isAllowed($requiredPermission->resourceName, $requiredPermission->permission)) {
            throw new ForbiddenException($requiredPermission->resourceName . ',' . $requiredPermission->permission->name);
        }

        return $invocation->proceed();
    }
}
