<?php

declare(strict_types=1);

namespace AppCore\Domain\Product;

interface ProductRepositoryInterface
{
    /** @param int<1, max> $id */
    public function findById(int $id): Product;

    /**
     * @param int<1, max> $storeId
     *
     * @return list<Product>
     */
    public function findByStoreId(int $storeId): array;

    /**
     * @param int<1, max>       $storeId
     * @param list<int<1, max>> $productIds
     *
     * @return list<Product>
     */
    public function findByStoreIdAndProductIds(int $storeId, array $productIds): array;

    public function insert(Product $product): void;

    public function update(Product $product): void;
}
