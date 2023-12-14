<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface OrderPickupCommandInterface
{
    /**
     * @param int<1, max> $orderId
     * @param int<1, max> $shopId
     * @param int<1, max> $staffMemberId
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('order_pickup_add', type: 'row')]
    public function add(
        int $orderId,
        DateTimeImmutable|null $pickupDate,
        string|null $pickupTime,
        int $shopId,
        string $shopName,
        int $staffMemberId,
        string $staffMemberName,
    ): array;
}
