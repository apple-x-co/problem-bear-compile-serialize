<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Salon;

class LoginInput
{
    /**
     * @psalm-suppress UndefinedAttributeClass
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     */
    public function __construct(
        public readonly string $username,
        public readonly string $password,
        public readonly string $remember,
        public readonly string $__csrf_token, // phpcs:ignore
        public readonly string|null $login,
    ) {
    }

    public function isValid(): bool
    {
        return $this->username !== '' && $this->password !== '';
    }

//    public function isSubmitted(): bool
//    {
//        return $this->login !== '';
//    }
}
