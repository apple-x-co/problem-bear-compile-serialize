<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

class ForgotCustomerPasswordInputData
{
    public function __construct(
        public readonly string $email,
    ) {
    }
}
