<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type PopularProductItem = array{
 *      id: string,
 *      store_id: string,
 *      product_id: string,
 *      position: string
 *  }
 */
interface PopularProductQueryInterface
{
    /**
     * @param int<1, max> $storeId
     *
     * @psalm-return list<PopularProductItem>
     */
    #[DbQuery('popular_product_list_by_store_id')]
    public function listByStoreId(int $storeId): array;
}
