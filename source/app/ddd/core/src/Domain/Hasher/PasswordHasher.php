<?php

declare(strict_types=1);

namespace AppCore\Domain\Hasher;

use function password_hash;

use const PASSWORD_BCRYPT;

class PasswordHasher implements PasswordHasherInterface
{
    public function hashType(): string
    {
        return PASSWORD_BCRYPT;
    }

    public function hash(string $text): string
    {
        return password_hash($text, PASSWORD_BCRYPT);
    }
}
