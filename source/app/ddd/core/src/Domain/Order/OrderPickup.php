<?php

declare(strict_types=1);

namespace AppCore\Domain\Order;

use DateTimeImmutable;

final class OrderPickup
{
    /**
     * @param int<1, max> $shopId
     * @param int<1, max> $staffMemberId
     * @param int<0, max> $id
     */
    public function __construct(
        public readonly DateTimeImmutable|null $pickupDate,
        public readonly string|null $pickupTime,
        public readonly int $shopId,
        public readonly string $shopName,
        public readonly int $staffMemberId,
        public readonly string $staffMemberName,
        public readonly int $id = 0,
    ) {
    }

    /**
     * @param int<1, max> $shopId
     * @param int<1, max> $staffMemberId
     * @param int<1, max> $id
     */
    public static function reconstruct(
        int $id,
        DateTimeImmutable|null $pickupDate,
        string|null $pickupTime,
        int $shopId,
        string $shopName,
        int $staffMemberId,
        string $staffMemberName,
    ): OrderPickup {
        return new self(
            $pickupDate,
            $pickupTime,
            $shopId,
            $shopName,
            $staffMemberId,
            $staffMemberName,
            $id,
        );
    }

    // TODO: add method
}
