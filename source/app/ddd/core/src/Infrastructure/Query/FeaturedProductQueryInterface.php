<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type FeaturedProductItem = array{
 *      id: string,
 *      store_id: string,
 *      product_id: string,
 *      position: string
 *  }
 */
interface FeaturedProductQueryInterface
{
    /**
     * @param int<1, max> $storeId
     *
     * @psalm-return list<FeaturedProductItem>
     */
    #[DbQuery('featured_product_list_by_store_id')]
    public function listByStoreId(int $storeId): array;
}
