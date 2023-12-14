<?php

declare(strict_types=1);

namespace AppCore\Domain;

use function bin2hex;
use function random_bytes;

final class Uuid
{
    public function __invoke(): string
    {
        return 'HELLO' . bin2hex(random_bytes(10)); // dummy code
    }
}
