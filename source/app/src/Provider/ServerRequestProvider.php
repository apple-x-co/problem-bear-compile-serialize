<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Provider;

use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;
use Ray\Di\ProviderInterface;

/**
 * ServerRequestInterface を提供
 *
 * @implements ProviderInterface<ServerRequestInterface>
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final class ServerRequestProvider implements ProviderInterface
{
    public function get(): ServerRequestInterface
    {
        return ServerRequest::fromGlobals();
    }
}
