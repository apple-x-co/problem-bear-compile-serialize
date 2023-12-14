<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface ProductShopStockCommandInterface
{
    /**
     * @param int<1, max> $productId
     * @param int<1, max> $shopId
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('product_shop_stock_add')]
    public function add(
        int $productId,
        int $shopId,
        string $status,
    ): array;
}
