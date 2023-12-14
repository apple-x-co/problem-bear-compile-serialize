<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type CustomerOrderItem = array{
 *     id: string,
 *      customer_id: string,
 *      store_id: string,
 *      order_id: string,
 * }
 */
interface CustomerOrderQueryInterface
{
    /**
     * @param int<1, max> $orderId
     *
     * @psalm-return CustomerOrderItem|null
     */
    #[DbQuery('customer_order_item_by_order_id', 'row')]
    public function itemByOrderId(int $orderId): array|null;

    /**
     * @param int<1, max> $storeId
     * @param int<1, max> $customerId
     *
     * @psalm-return list<CustomerOrderItem>
     */
    #[DbQuery('customer_order_list_by_store_customer')]
    public function listByStoreCustomer(int $storeId, int $customerId): array;
}
