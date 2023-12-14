<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

final class ResetCustomerPasswordInputData
{
    public function __construct(
        public readonly string $phoneNumber,
        public readonly string $password,
        public readonly string $signature,
    ) {
    }
}
