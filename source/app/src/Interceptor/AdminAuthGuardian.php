<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Interceptor;

use AppCore\Domain\AdminSession\AdminGenericSessionStoreInterface;
use BEAR\Resource\NullRenderer;
use BEAR\Resource\ResourceObject;
use BEAR\Sunday\Extension\Router\RouterInterface;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Auth\AdminAuthenticatorInterface;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;

use function assert;
use function is_string;

class AdminAuthGuardian implements MethodInterceptor
{
    public function __construct(
        private readonly AdminAuthenticatorInterface $authenticator,
        private readonly AdminGenericSessionStoreInterface $genericSessionStore,
        private readonly RouterInterface $router,
    ) {
    }

    public function invoke(MethodInvocation $invocation): mixed
    {
        if ($this->authenticator->isValid()) {
            return $invocation->proceed();
        }

        $ro = $invocation->getThis();
        assert($ro instanceof ResourceObject);

        $uri = $ro->uri;
        if ($uri->method === 'get') {
            $path = $this->router->generate($uri->path, $uri->query);

            if (is_string($path)) {
                $genericSession = $this->genericSessionStore->get();
                $genericSession = $genericSession->changeContinueUrlPath($path);
                $this->genericSessionStore->update($genericSession);
            }
        }

        $ro->setRenderer(new NullRenderer());
        $ro->code = StatusCode::FOUND;
        $ro->headers = [
            'Location' => $this->authenticator->isExpired() ?
                $this->authenticator->getUnauthRedirect() . '?expired' :
                $this->authenticator->getUnauthRedirect() . '?unauthenticated',
        ];
        $ro->view = '';
        $ro->body = [];

        return $ro;
    }
}
