<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type ProductImageItem = array{
 *       id: string,
 *       product_id: string,
 *       group: string,
 *       size: string,
 *       media_type: string,
 *       width: string,
 *       height: string,
 *       original_file_name: string,
 *       url: string,
 *       path: string,
 *       file_name: string,
 *       md5: string,
 *       position: string,
 *   }
 */
interface ProductImageQueryInterface
{
    /**
     * @param int<1, max> $id
     *
     * @psalm-return ProductImageItem|null
     */
    #[DbQuery('product_image_item', type: 'row')]
    public function item(int $id): array|null;

    /**
     * @param int<1, max> $productId
     *
     * @psalm-return list<ProductImageItem>
     */
    #[DbQuery('product_image_list_by_product_id')]
    public function listByProductId(int $productId): array;

    /**
     * @param int<1, max> $storeId
     *
     * @psalm-return list<ProductImageItem>
     */
    #[DbQuery('product_image_list_by_store_id')]
    public function listByStoreId(int $storeId): array;
}
