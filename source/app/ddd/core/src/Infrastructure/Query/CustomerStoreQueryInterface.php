<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type CustomerStoreItem = array{
 *     id: string,
 *      customer_id: string,
 *      store_id: string,
 *      shop_id: string|null,
 *      staff_member_id: string|null,
 *      last_order_date: string|null,
 *      number_of_orders: string,
 *      number_of_order_cancels: string,
 *      remaining_point: string,
 *      customer_note: string|null
 * }
 */
interface CustomerStoreQueryInterface
{
    /**
     * @param int<1, max> $customerId
     * @param int<1, max> $storeId
     *
     * @psalm-return CustomerStoreItem|null
     */
    #[DbQuery('customer_store_item_by_unique_key', type: 'row')]
    public function itemByUniqueKey(int $customerId, int $storeId): array|null;
}
