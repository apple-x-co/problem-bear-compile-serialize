<?php

declare(strict_types=1);

namespace MyVendor\MyProject\TemplateEngine;

use BEAR\Sunday\Extension\Router\RouterInterface;
use Qiq\Helper\Html\HtmlHelpers;

/** @SuppressWarnings(PHPMD.LongVariable) */
class QiqCustomHelpers extends HtmlHelpers
{
    public function __construct(
        private readonly RouterInterface $router,
    ) {
        parent::__construct(null);
    }
}
