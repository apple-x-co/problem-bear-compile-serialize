<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type ProductTaxonomyItem = array{
 *      id: string,
 *      product_id: string,
 *      taxonomy_id: string,
 *      position: string,
 *  }
 */
interface ProductTaxonomyQueryInterface
{
    /**
     * @param int<1, max> $id
     *
     * @psalm-return ProductTaxonomyItem|null
     */
    #[DbQuery('product_taxonomy_item', type: 'row')]
    public function item(int $id): array|null;

    /**
     * @param int<1, max> $productId
     *
     * @psalm-return list<ProductTaxonomyItem>
     */
    #[DbQuery('product_taxonomy_list_by_product_id')]
    public function listByProductId(int $productId): array;

    /**
     * @param int<1, max> $storeId
     *
     * @psalm-return list<ProductTaxonomyItem>
     */
    #[DbQuery('product_taxonomy_list_by_store_id')]
    public function listByStoreId(int $storeId): array;
}
