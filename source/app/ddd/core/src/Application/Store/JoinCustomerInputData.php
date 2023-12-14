<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

final class JoinCustomerInputData
{
    public function __construct(
        public readonly string $genderType,
        public readonly string $familyName,
        public readonly string $givenName,
        public readonly string $phoneticFamilyName,
        public readonly string $phoneticGivenName,
        public readonly string $phoneNumber,
        public readonly string $email,
        public readonly string $password,
    ) {
    }
}
