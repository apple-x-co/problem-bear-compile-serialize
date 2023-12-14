<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type StoreUsageItem = array{
 *     id: string,
 *     store_id: string,
 *     description: string,
 *     target_date: string,
 *     total_price: string,
 *     status: string,
 * }
 */
interface StoreUsageQueryInterface
{
    /**
     * @param int<1, max> $id
     *
     * @return StoreUsageItem|null
     */
    #[DbQuery('store_usage_item', type: 'row')]
    public function item(int $id): array|null;

    /**
     * @param int<1, max> $storeId
     *
     * @return list<StoreUsageItem>
     */
    #[DbQuery('store_usage_list_by_store_id')]
    public function listByStoreId(int $storeId): array;
}
