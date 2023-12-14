<?php

declare(strict_types=1);

namespace AppCore\Domain\StoreUsage;

interface StoreUsageRepositoryInterface
{
    /** @param int<1, max> $id */
    public function findById(int $id): StoreUsage;

    /**
     * @param int<1, max> $storeId
     *
     * @return list<StoreUsage>
     */
    public function findByStoreId(int $storeId): array;

    public function insert(StoreUsage $storeUsage): void;

    public function update(StoreUsage $storeUsage): void;
}
