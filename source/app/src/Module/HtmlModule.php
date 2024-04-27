<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use BEAR\Package\AbstractAppModule;
use MyVendor\MyProject\TemplateEngine\QiqAdminHelpers;
use MyVendor\MyProject\TemplateEngine\QiqCustomHelpers;
use MyVendor\MyProject\TemplateEngine\QiqErrorModule;
use MyVendor\MyProject\TemplateEngine\QiqModule;
use MyVendor\MyProject\TemplateEngine\QiqSalonHelpers;
use MyVendor\MyProject\TemplateEngine\QiqStoreHelpers;
use Qiq\Helpers;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
final class HtmlModule extends AbstractAppModule
{
    protected function configure(): void
    {
        $this->renderer();
    }

    private function renderer(): void
    {
        $this->bind(QiqAdminHelpers::class);
        $this->bind(QiqStoreHelpers::class);
        $this->bind(QiqSalonHelpers::class);
        $this->bind(Helpers::class)->to(QiqCustomHelpers::class);
        $this->install(new QiqModule([$this->appMeta->appDir . '/var/qiq/template']));
        $this->install(new QiqErrorModule('DebugTrace'));
    }
}
