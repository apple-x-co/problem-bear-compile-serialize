<?php

declare(strict_types=1);

namespace AppCore\Domain\FeaturedProduct;

interface FeaturedProductRepositoryInterface
{
    /**
     * @param int<1, max> $storeId
     *
     * @return list<FeaturedProduct>
     */
    public function findByStoreId(int $storeId): array;

    public function insert(FeaturedProduct $featuredProduct): void;

    public function update(FeaturedProduct $featuredProduct): void;
}
