<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type CustomerFavoriteItem = array{
 *     id: string,
 *      customer_id: string,
 *      store_id: string,
 *      product_id: string,
 *      favorited_date: string,
 * }
 */
interface CustomerFavoriteProductQueryInterface
{
    /**
     * @param int<1, max> $customerId
     * @param int<1, max> $productId
     *
     * @psalm-return CustomerFavoriteItem|null
     */
    #[DbQuery('customer_favorite_product_item_by_unique_key', type: 'row')]
    public function itemByUniqueKey(int $customerId, int $productId): array|null;

    /**
     * @param int<1, max> $storeId
     * @param int<1, max> $customerId
     *
     * @psalm-return list<CustomerFavoriteItem>
     */
    #[DbQuery('customer_favorite_product_list_by_store_customer')]
    public function listByStoreCustomer(int $storeId, int $customerId): array;
}
