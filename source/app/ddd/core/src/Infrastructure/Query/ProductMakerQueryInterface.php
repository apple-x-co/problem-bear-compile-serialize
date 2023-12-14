<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type ProductMakerItem = array{
 *      id: string,
 *      product_id: string,
 *      maker_id: string,
 *      position: string,
 *  }
 */
interface ProductMakerQueryInterface
{
    /**
     * @param int<1, max> $id
     *
     * @psalm-return ProductMakerItem|null
     */
    #[DbQuery('product_maker_item', type: 'row')]
    public function item(int $id): array|null;

    /**
     * @param int<1, max> $productId
     *
     * @psalm-return list<ProductMakerItem>
     */
    #[DbQuery('product_maker_list_by_product_id')]
    public function listByProductId(int $productId): array;

    /**
     * @param int<1, max> $storeId
     *
     * @psalm-return list<ProductMakerItem>
     */
    #[DbQuery('product_maker_list_by_store_id')]
    public function listByStoreId(int $storeId): array;
}
