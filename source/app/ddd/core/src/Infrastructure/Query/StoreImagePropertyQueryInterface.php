<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type StoreImagePropertyItem = array{
 *     id: string,
 *     store_image_id: string,
 *     name: string,
 *     value: string,
 * }
 */
interface StoreImagePropertyQueryInterface
{
    /**
     * @param int<1, max> $storeId
     *
     * @return list<StoreImagePropertyItem>
     */
    #[DbQuery('store_image_property_list_by_store_id')]
    public function listByStoreId(int $storeId): array;
}
