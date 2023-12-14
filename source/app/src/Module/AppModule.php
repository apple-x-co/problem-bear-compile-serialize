<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use BEAR\Package\AbstractAppModule;
use BEAR\Package\PackageModule;
use BEAR\Package\Provide\Router\AuraRouterModule;
use BEAR\Resource\Module\JsonSchemaModule;
use Koriym\EnvJson\EnvJson;
use MyVendor\MyProject\Annotation\Qualifier\PdoDns;
use MyVendor\MyProject\Annotation\Qualifier\PdoPassword;
use MyVendor\MyProject\Annotation\Qualifier\PdoUsername;
use Ray\AuraSqlModule\AuraSqlModule;
use Ray\IdentityValueModule\IdentityValueModule;
use Ray\MediaQuery\DbQueryConfig;
use Ray\MediaQuery\MediaQueryModule;
use Ray\MediaQuery\Queries;

use function dirname;
use function getenv;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class AppModule extends AbstractAppModule
{
    protected function configure(): void
    {
        (new EnvJson())->load(dirname(__DIR__, 2));

        $appDir = $this->appMeta->appDir;

        $this->router($appDir);

        $this->database($appDir);

        $this->json($appDir);

        $this->install(new BaseModule(
            $this->appMeta->appDir . '/var/lang',
            $this->appMeta->appDir . '/var/email',
        ));

        $this->install(new IdentityValueModule());

        $this->install(new PackageModule());

        $this->install(new DefaultModule());
    }

    private function router(string $appDir): void
    {
        $this->install(
            new AuraRouterModule($appDir . '/var/conf/aura.route.php'),
        );
    }

    /** @SuppressWarnings(PHPMD.StaticAccess) */
    private function database(string $appDir): void
    {
        $this->bind()->annotatedWith(PdoDns::class)->toInstance((string) getenv('DB_DSN'));
        $this->bind()->annotatedWith(PdoUsername::class)->toInstance((string) getenv('DB_USER'));
        $this->bind()->annotatedWith(PdoPassword::class)->toInstance((string) getenv('DB_PASS'));

        $this->install(
            new AuraSqlModule(
                (string) getenv('DB_DSN'),
                (string) getenv('DB_USER'),
                (string) getenv('DB_PASS'),
                (string) getenv('DB_SLAVE'),
                [],
                [/** @lang SQL */ 'SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED'],
            ),
        );

        $this->install(
            new MediaQueryModule(
                Queries::fromDir($appDir . '/ddd/core/src/Infrastructure/Query'),
                [new DbQueryConfig($appDir . '/var/sql')],
            ),
        );
    }

    private function json(string $appDir): void
    {
        $this->install(
            new JsonSchemaModule(
                $appDir . '/var/schema/response',
                $appDir . '/var/schema/request',
            ),
        );
    }
}
