<?php

declare(strict_types=1);

namespace AppCore\Domain\SecureRandom;

interface SecureRandomInterface
{
    public function hash(string $data, string $algo = 'sha256'): string;

    public function shortHash(string $data, string $algo = 'crc32'): string;

    public function hmac(string $data, string $algo = 'sha256'): string;

    /** @param int<1, max> $length */
    public function randomBytes(int $length): string;

    /** @param int<1, max> $length */
    public function randomNumbers(int $length): int;
}
