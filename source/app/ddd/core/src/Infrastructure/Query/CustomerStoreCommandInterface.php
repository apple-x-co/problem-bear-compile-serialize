<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface CustomerStoreCommandInterface
{
    /**
     * @param int<1, max>      $customerId
     * @param int<1, max>      $storeId
     * @param int<1, max>|null $shopId
     * @param int<1, max>|null $staffMemberId
     * @param int<0, max>      $numberOfOrders
     * @param int<0, max>      $numberOfOrderCancels
     * @param int<0, max>      $remainingPoint
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('customer_store_add', 'row')]
    public function add(
        int $customerId,
        int $storeId,
        int|null $shopId,
        int|null $staffMemberId,
        DateTimeImmutable|null $lastOrderDate,
        int $numberOfOrders,
        int $numberOfOrderCancels,
        int $remainingPoint,
        string|null $customerNote,
    ): array;

    /**
     * @param int<1, max>|null $shopId
     * @param int<1, max>|null $staffMemberId
     * @param int<0, max>      $numberOfOrders
     * @param int<0, max>      $numberOfOrderCancels
     * @param int<0, max>      $remainingPoint
     */
    #[DbQuery('customer_store_update', 'row')]
    public function update(
        int $id,
        int|null $shopId,
        int|null $staffMemberId,
        DateTimeImmutable|null $lastOrderDate,
        int $numberOfOrders,
        int $numberOfOrderCancels,
        int $remainingPoint,
        string|null $customerNote,
    ): void;
}
