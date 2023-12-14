<?php

declare(strict_types=1);

namespace AppCore\Domain\Company;

final class CompanyContact
{
    public function __construct(
        public readonly string $postalCode,
        public readonly string $state,
        public readonly string $city,
        public readonly string $addressLine1,
        public readonly string $addressLine2,
        public readonly string $phoneNumber,
        public readonly string $representativeName,
        public readonly string $representativeEmail,
    ) {
    }

    public static function reconstruct(
        string $postalCode,
        string $state,
        string $city,
        string $addressLine1,
        string $addressLine2,
        string $phoneNumber,
        string $representativeName,
        string $representativeEmail,
    ): CompanyContact {
        return new self(
            $postalCode,
            $state,
            $city,
            $addressLine1,
            $addressLine2,
            $phoneNumber,
            $representativeName,
            $representativeEmail,
        );
    }
}
