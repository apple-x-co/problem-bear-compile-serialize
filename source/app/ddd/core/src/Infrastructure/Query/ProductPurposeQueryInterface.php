<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type ProductPurposeItem = array{
 *      id: string,
 *      product_id: string,
 *      purpose_id: string,
 *      position: string,
 *  }
 */
interface ProductPurposeQueryInterface
{
    /**
     * @param int<1, max> $id
     *
     * @psalm-return ProductPurposeItem|null
     */
    #[DbQuery('product_purpose_item', type: 'row')]
    public function item(int $id): array|null;

    /**
     * @param int<1, max> $productId
     *
     * @psalm-return list<ProductPurposeItem>
     */
    #[DbQuery('product_purpose_list_by_product_id')]
    public function listByProductId(int $productId): array;

    /**
     * @param int<1, max> $storeId
     *
     * @psalm-return list<ProductPurposeItem>
     */
    #[DbQuery('product_purpose_list_by_store_id')]
    public function listByStoreId(int $storeId): array;
}
