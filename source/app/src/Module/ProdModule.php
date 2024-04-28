<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use BEAR\Package\AbstractAppModule;
use BEAR\Package\Context\ProdModule as PackageProdModule;
use BEAR\QueryRepository\CacheVersionModule;
use BEAR\QueryRepository\StorageMemcachedModule;
use BEAR\RepositoryModule\Annotation\EtagPool;
use BEAR\Resource\Module\OptionsMethodModule;
use MyVendor\MyProject\TemplateEngine\QiqErrorModule;
use MyVendor\MyProject\TemplateEngine\QiqProdModule;
use Psr\Cache\CacheItemPoolInterface;

use function getenv;
use function is_string;
use function time;

final class ProdModule extends AbstractAppModule
{
    protected function configure(): void
    {
        $appDir = $this->appMeta->appDir;

        $this->install(new PackageProdModule());
        $this->override(new OptionsMethodModule());
        $this->install(new CacheVersionModule((string) time()));
        $this->cache();
        $this->renderer($appDir);
    }

    private function cache(): void
    {
        $memcachedServers = getenv('MEMCACHED_SERVERS');
        if (! is_string($memcachedServers) || $memcachedServers === '') {
            return;
        }

        // $this->bind(CacheItemPoolInterface::class)->annotatedWith(EtagPool::class)->toInstance(null); // NOTE: コンパイル後も "Failed to verify the injector cache." が出てしまう現象の回避
        $this->install(new StorageMemcachedModule($memcachedServers));
    }

    private function renderer(string $appDir): void
    {
        $this->install(new QiqErrorModule());
        $this->install(new QiqProdModule($appDir . '/var/tmp'));
    }
}
