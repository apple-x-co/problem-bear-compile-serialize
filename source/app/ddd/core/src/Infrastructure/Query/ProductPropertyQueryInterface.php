<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type ProductPropertyItem = array{
 *      id: string,
 *      product_id: string,
 *      name: string,
 *      value: string
 *  }
 */
interface ProductPropertyQueryInterface
{
    /**
     * @param int<1, max> $id
     *
     * @psalm-return ProductPropertyItem|null
     */
    #[DbQuery('product_property_item', type: 'row')]
    public function item(int $id): array|null;

    /**
     * @param int<1, max> $productId
     *
     * @psalm-return list<ProductPropertyItem>
     */
    #[DbQuery('product_property_list_by_product_id')]
    public function listByProductId(int $productId): array;

    /**
     * @param int<1, max> $storeId
     *
     * @psalm-return list<ProductPropertyItem>
     */
    #[DbQuery('product_property_list_by_store_id')]
    public function listByStoreId(int $storeId): array;
}
