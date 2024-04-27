<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use BEAR\Package\AbstractAppModule;
use BEAR\Package\PackageModule;
use Koriym\EnvJson\EnvJson;
use Ray\IdentityValueModule\IdentityValueModule;

use function dirname;
use function getenv;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class AppModule extends AbstractAppModule
{
    protected function configure(): void
    {
        (new EnvJson())->load(dirname(__DIR__, 2));

        $this->install(new PackageModule());
    }
}
