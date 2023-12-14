<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Auth;

class SalonIdentity
{
    public function __construct(
        public readonly int $id,
        public readonly string $displayName,
    ) {
    }
}
