<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Admin;

class PasswordInput
{
    /**
     * @psalm-suppress UndefinedAttributeClass
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     */
    public function __construct(
        public readonly string $password,
        public readonly string $__csrf_token, // phpcs:ignore
        public readonly string|null $continue,
    ) {
    }
}
