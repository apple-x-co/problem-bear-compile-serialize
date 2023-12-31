<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Auth;

class AdminIdentity
{
    public function __construct(
        public readonly int $id,
        public readonly string $displayName,
    ) {
    }
}
