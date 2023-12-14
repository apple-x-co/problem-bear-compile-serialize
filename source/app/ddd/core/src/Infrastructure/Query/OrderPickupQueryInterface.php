<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type OrderPickupItem = array{
 *      id: string,
 *      order_id: string,
 *      pickup_date: string,
 *      pickup_time: string,
 *      shop_id: string,
 *      shop_name: string,
 *      staff_member_id: string,
 *      staff_member_name: string,
 * }
 */
interface OrderPickupQueryInterface
{
    /**
     * @param int<1, max> $orderId
     *
     * @psalm-return OrderPickupItem|null
     */
    #[DbQuery('order_pickup_item_by_order_id', type: 'row')]
    public function itemByOrderId(int $orderId): array|null;
}
