<?php

declare(strict_types=1);

namespace AppCore\Presentation\Shared;

// Encrypt
interface EncryptCookiesInterface
{
    public function get(string $name): string|null;

    /** @param int<1, max> $hours */
    public function set(
        string $name,
        string $value,
        int $hours,
        string $path = '/',
        string $sameSite = 'strict',
    ): void;

    public function revoke(
        string $name,
        string $path = '/',
        string $sameSite = 'strict',
    ): void;
}
