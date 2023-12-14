<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Domain\LoggerInterface;
use BEAR\AppMeta\AbstractAppMeta;
use DateTimeInterface;

use function error_log;

use const PHP_EOL;

class AdminLogger implements LoggerInterface
{
    public function __construct(
        private readonly AbstractAppMeta $appMeta,
        private readonly DateTimeInterface $now,
    ) {
    }

    public function log(string $message): void
    {
        error_log(
            $this->now->format('Y-m-d H:i:s') . ' ' . $message . PHP_EOL,
            3,
            $this->appMeta->logDir . '/admin.log',
        );
    }
}
