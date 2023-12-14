<?php

declare(strict_types=1);

namespace AppCore\Domain\CustomerStore;

use DateTimeImmutable;

final class CustomerStore
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<1, max>      $customerId
     * @param int<1, max>      $storeId
     * @param int<1, max>|null $shopId
     * @param int<1, max>|null $staffMemberId
     * @param int<1, max>      $numberOfOrders
     * @param int<1, max>      $numberOfOrderCancels
     * @param int<1, max>      $remainingPoint
     * @param int<0, max>      $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        public readonly int $customerId,
        public readonly int $storeId,
        public readonly int|null $shopId,
        public readonly int|null $staffMemberId,
        public readonly DateTimeImmutable|null $lastOrderDate,
        public readonly int $numberOfOrders,
        public readonly int $numberOfOrderCancels,
        public readonly int $remainingPoint,
        public readonly string|null $customerNote,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max>      $customerId
     * @param int<1, max>      $storeId
     * @param int<1, max>|null $shopId
     * @param int<1, max>|null $staffMemberId
     * @param int<1, max>      $numberOfOrders
     * @param int<1, max>      $numberOfOrderCancels
     * @param int<1, max>      $remainingPoint
     * @param int<0, max>      $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public static function reconstruct(
        int $id,
        int $customerId,
        int $storeId,
        int|null $shopId,
        int|null $staffMemberId,
        DateTimeImmutable|null $lastOrderDate,
        int $numberOfOrders,
        int $numberOfOrderCancels,
        int $remainingPoint,
        string|null $customerNote,
    ): CustomerStore {
        return new self(
            $customerId,
            $storeId,
            $shopId,
            $staffMemberId,
            $lastOrderDate,
            $numberOfOrders,
            $numberOfOrderCancels,
            $remainingPoint,
            $customerNote,
            $id,
        );
    }

    /** @return int<1, max>|null */
    public function getNewId(): int|null
    {
        return $this->newId;
    }

    /** @param int<1, max> $newId */
    public function setNewId(int $newId): void
    {
        $this->newId = $newId;
    }
}
