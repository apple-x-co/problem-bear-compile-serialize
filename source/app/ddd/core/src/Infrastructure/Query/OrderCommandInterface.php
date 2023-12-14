<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface OrderCommandInterface
{
    /**
     * @param int<1, max>       $storeId
     * @param int<min, -1>|null $discountPrice
     * @param int<1, 100>|null  $pointRate
     * @param int<min, 0>|null  $spendingPoint
     * @param int<0, max>|null  $earningPoint
     * @param int<0, max>       $totalPrice
     * @param int<0, max>       $totalTax
     * @param int<0, max>       $subtotalPrice
     * @param int<1, max>       $paymentMethodId
     * @param int<0, max>       $paymentFee
     *
     * @return array{id: int<1, max>}
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    #[DbQuery('order_add', 'row')]
    public function add(
        int $storeId,
        string $orderNo,
        DateTimeImmutable $orderDate,
        DateTimeImmutable|null $closeDate,
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
        string|null $discountCode,
        int|null $discountPrice,
        int|null $pointRate,
        int|null $spendingPoint,
        int|null $earningPoint,
        int $totalPrice,
        int $totalTax,
        int $subtotalPrice,
        int $paymentMethodId,
        string $paymentMethodName,
        int $paymentFee,
        string|null $note,
        string|null $orderNote,
        string $pickupStatus,
        string $status,
    ): array;

    /** @param int<1, max> $id */
    #[DbQuery('order_update', 'row')]
    public function update(
        int $id,
        DateTimeImmutable|null $closeDate,
        string|null $note,
        string|null $orderNote,
        string $pickupStatus,
        string $status,
    ): void;
}
