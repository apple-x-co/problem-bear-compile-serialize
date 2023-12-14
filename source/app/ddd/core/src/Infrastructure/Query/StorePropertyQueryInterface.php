<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type StorePropertyItem = array{
 *     id: string,
 *     store_id: string,
 *     name: string,
 *     value: string,
 * }
 */
interface StorePropertyQueryInterface
{
    /**
     * @param int<1, max> $storeId
     *
     * @return list<StorePropertyItem>
     */
    #[DbQuery('store_property_list_by_store_id')]
    public function listByStoreId(int $storeId): array;
}
