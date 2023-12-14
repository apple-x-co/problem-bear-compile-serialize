<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type ProductItem = array{
 *      id: string,
 *      store_id: string,
 *      title: string,
 *      code: string,
 *      taxable: string,
 *      tax_id: string,
 *      original_price: string,
 *      price: string,
 *      number_of_pieces: string,
 *      discount_rate: string,
 *      discount_price: string,
 *      stockist_notification_enabled: string,
 *      stockist_name: string,
 *      sale_start_date: string,
 *      sale_end_date: string,
 *      status: string,
 *  }
 */
interface ProductQueryInterface
{
    /**
     * @param int<1, max> $id
     *
     * @psalm-return ProductItem|null
     */
    #[DbQuery('product_item', type: 'row')]
    public function item(int $id): array|null;

    /**
     * @param int<1, max> $storeId
     *
     * @psalm-return list<ProductItem>
     */
    #[DbQuery('product_list_by_store_id')]
    public function listByStoreId(int $storeId): array;

    /**
     * @param int<1, max>        $storeId
     * @param array<int<1, max>> $productIds
     *
     * @psalm-return list<ProductItem>
     */
    #[DbQuery('product_list_by_store_id_product_ids')]
    public function listByStoreIdAndProductIds(int $storeId, array $productIds): array;
}
