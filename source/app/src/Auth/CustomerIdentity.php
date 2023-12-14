<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Auth;

class CustomerIdentity
{
    /** @param int<1, max> $id */
    public function __construct(
        public readonly int $id,
        public readonly string $displayName,
    ) {
    }
}
