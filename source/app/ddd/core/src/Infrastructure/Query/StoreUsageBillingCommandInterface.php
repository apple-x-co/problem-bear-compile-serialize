<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

/** @SuppressWarnings(PHPMD.ExcessiveParameterList) */
interface StoreUsageBillingCommandInterface
{
    /**
     * @param int<1, max> $storeUsageId
     * @param int<1, max> $chargeAmount
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('store_usage_billing_add', type: 'row')]
    public function add(
        int $storeUsageId,
        int $chargeAmount,
        DateTimeImmutable $billingDate,
        DateTimeImmutable $scheduledPaymentDate,
        DateTimeImmutable $dueDate,
        DateTimeImmutable|null $paidDate,
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
        string $status,
    ): array;

    /** @param int<1, max> $id */
    #[DbQuery('store_usage_billing_update', type: 'row')]
    public function update(int $id, DateTimeImmutable|null $paidDate, string $status): void;
}
