<?php

declare(strict_types=1);

namespace MyVendor\MyProject\TemplateEngine;

use BEAR\Resource\RenderInterface;
use BEAR\Resource\ResourceObject;
use Ray\Di\Di\Inject;
use Ray\Di\Di\Named;

class QiqErrorPage extends ResourceObject
{
    // phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
    /** @var array<string, string>  */
    public $headers = ['content-type' => 'text/html; charset=utf-8'];
    // phpcs:enable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint

    /**
     * {@inheritDoc}
     */
    public function __sleep(): array
    {
        return ['renderer'];
    }

    #[Inject]
    #[Named('error_page')]
    public function setRenderer(RenderInterface $renderer): ResourceObject
    {
        return parent::setRenderer($renderer);
    }
}
