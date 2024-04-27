<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use BEAR\Package\AbstractAppModule;
use BEAR\Package\PackageModule;
use Koriym\EnvJson\EnvJson;

use Qiq\Helpers;

use function dirname;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class AppModule extends AbstractAppModule
{
    protected function configure(): void
    {
        (new EnvJson())->load(dirname(__DIR__, 2));

        $this->install(new PackageModule());

        // default bind
        $this->bind()->annotatedWith('qiq_extension')->toInstance('.php');
        $this->bind()->annotatedWith('qiq_paths')->toInstance([]);
        $this->bind(Helpers::class)->to(Helpers::class);
    }
}
