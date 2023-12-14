<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type ProductVariantItem = array{
 *      id: string,
 *      product_id: string,
 *      title: string,
 *      code: string,
 *      sku: string,
 *      original_price: string,
 *      price: string,
 *      discount_price: string|null,
 *      pickup_duration_days: string,
 *  }
 */
interface ProductVariantQueryInterface
{
    /**
     * @param int<1, max> $id
     *
     * @psalm-return ProductVariantItem|null
     */
    #[DbQuery('product_variant_item', type: 'row')]
    public function item(int $id): array|null;

    /**
     * @param int<1, max> $productId
     *
     * @psalm-return list<ProductVariantItem>
     */
    #[DbQuery('product_variant_list_by_product_id')]
    public function listByProductId(int $productId): array;

    /**
     * @param int<1, max> $storeId
     *
     * @psalm-return list<ProductVariantItem>
     */
    #[DbQuery('product_variant_list_by_store_id')]
    public function listByStoreId(int $storeId): array;
}
