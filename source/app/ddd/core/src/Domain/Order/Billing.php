<?php

declare(strict_types=1);

namespace AppCore\Domain\Order;

use DateTimeImmutable;

final class Billing
{
    /**
     * @param int<0, max> $chargeAmount
     * @param int<0, max> $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        public readonly string $billingNo,
        public readonly int $chargeAmount,
        public readonly DateTimeImmutable $billingDate,
        public readonly string $familyName,
        public readonly string $givenName,
        public readonly string $phoneticFamilyName,
        public readonly string $phoneticGivenName,
        public readonly string $postalCode,
        public readonly string $state,
        public readonly string $city,
        public readonly string $addressLine1,
        public readonly string $addressLine2,
        public readonly string $phoneNumber,
        public readonly string $email,
        public readonly int $id = 0,
    ) {
    }

    /**
     * @param int<0, max> $chargeAmount
     * @param int<1, max> $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public static function reconstruct(
        int $id,
        string $billingNo,
        int $chargeAmount,
        DateTimeImmutable $billingDate,
        string $familyName,
        string $givenName,
        string $phoneticFamilyName,
        string $phoneticGivenName,
        string $postalCode,
        string $state,
        string $city,
        string $addressLine1,
        string $addressLine2,
        string $phoneNumber,
        string $email,
    ): Billing {
        return new self(
            $billingNo,
            $chargeAmount,
            $billingDate,
            $familyName,
            $givenName,
            $phoneticFamilyName,
            $phoneticGivenName,
            $postalCode,
            $state,
            $city,
            $addressLine1,
            $addressLine2,
            $phoneNumber,
            $email,
            $id,
        );
    }

    // TODO: add method
}
