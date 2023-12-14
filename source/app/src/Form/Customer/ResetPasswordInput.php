<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Customer;

class ResetPasswordInput
{
    /** @SuppressWarnings(PHPMD.CamelCaseParameterName) */
    public function __construct(
        public readonly string $phoneNumber,
        public readonly string $password,
        public readonly string $signature,
        public readonly string $__csrf_token, // phpcs:ignore
        public readonly string|null $continue = null,
    ) {
    }
}
