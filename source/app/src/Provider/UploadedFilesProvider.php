<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Provider;

use GuzzleHttp\Psr7\ServerRequest;
use MyVendor\MyProject\Form\UploadFiles;
use MyVendor\MyProject\Form\UploadFilesInterface;
use Ray\Di\ProviderInterface;

/** @implements ProviderInterface<UploadFilesInterface> */
class UploadedFilesProvider implements ProviderInterface
{
    public function get(): UploadFilesInterface
    {
        return new UploadFiles(ServerRequest::fromGlobals()->getUploadedFiles());
    }
}
