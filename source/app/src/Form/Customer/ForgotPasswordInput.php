<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Customer;

class ForgotPasswordInput
{
    /** @SuppressWarnings(PHPMD.CamelCaseParameterName) */
    public function __construct(
        public readonly string $email,
        public readonly string $__csrf_token, // phpcs:ignore
        public readonly string|null $continue = null,
    ) {
    }
}
