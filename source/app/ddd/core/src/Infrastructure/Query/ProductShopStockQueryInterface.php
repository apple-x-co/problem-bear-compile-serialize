<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type ProductShopStockItem = array{
 *      id: string,
 *      product_id: string,
 *      shop_id: string,
 *      status: string
 *   }
 */
interface ProductShopStockQueryInterface
{
    /**
     * @param int<1, max> $productId
     *
     * @psalm-return list<ProductShopStockItem>
     */
    #[DbQuery('product_shop_stock_list_by_product_id')]
    public function listByProductId(int $productId): array;
}
