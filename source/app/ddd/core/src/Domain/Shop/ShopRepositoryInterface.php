<?php

declare(strict_types=1);

namespace AppCore\Domain\Shop;

interface ShopRepositoryInterface
{
    /** @param int<1, max> $id */
    public function findById(int $id): Shop;

    /**
     * @param int<1, max> $companyId
     *
     * @return list<Shop>
     */
    public function findByCompanyId(int $companyId): array;

    public function insert(Shop $shop): void;

    public function update(Shop $shop): void;
}
