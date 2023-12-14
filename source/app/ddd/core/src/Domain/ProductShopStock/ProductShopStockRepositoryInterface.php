<?php

declare(strict_types=1);

namespace AppCore\Domain\ProductShopStock;

interface ProductShopStockRepositoryInterface
{
    /**
     * @param int<1, max> $productId
     *
     * @return list<ProductShopStock>
     */
    public function findByProductId(int $productId): array;

    public function insert(ProductShopStock $productShopStock): void;
}
