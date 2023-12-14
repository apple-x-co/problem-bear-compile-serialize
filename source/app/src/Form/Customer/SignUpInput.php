<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Customer;

class SignUpInput
{
    /**
     * @psalm-suppress UndefinedAttributeClass
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        public readonly string $familyName,
        public readonly string $givenName,
        public readonly string $phoneticFamilyName,
        public readonly string $phoneticGivenName,
        public readonly string $phoneNumber,
        public readonly string $email,
        public readonly string $password,
        public readonly string $__csrf_token, // phpcs:ignore
        public readonly string|null $genderType = null,
        public readonly string|null $agree = null,
        public readonly string|null $continue = null,
        public readonly string|null $back = null,
        public readonly string|null $complete = null,
    ) {
    }
}
