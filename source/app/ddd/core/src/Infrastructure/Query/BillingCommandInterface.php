<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface BillingCommandInterface
{
    /**
     * @param int<1, max> $orderId
     * @param int<0, max> $chargeAmount
     *
     * @return array{id: int<1, max>}
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    #[DbQuery('billing_add', type: 'row')]
    public function add(
        int $orderId,
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
    ): array;
}
