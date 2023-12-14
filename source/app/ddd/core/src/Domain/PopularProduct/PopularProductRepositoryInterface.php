<?php

declare(strict_types=1);

namespace AppCore\Domain\PopularProduct;

interface PopularProductRepositoryInterface
{
    /**
     * @param int<1, max> $storeId
     *
     * @return list<PopularProduct>
     */
    public function findByStoreId(int $storeId): array;

    public function insert(PopularProduct $popularProduct): void;

    public function update(PopularProduct $popularProduct): void;
}
