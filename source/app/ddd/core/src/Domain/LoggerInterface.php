<?php

declare(strict_types=1);

namespace AppCore\Domain;

interface LoggerInterface
{
    public function log(string $message): void;
}
