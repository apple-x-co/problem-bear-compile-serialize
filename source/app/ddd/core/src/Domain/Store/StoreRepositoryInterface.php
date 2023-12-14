<?php

declare(strict_types=1);

namespace AppCore\Domain\Store;

interface StoreRepositoryInterface
{
    /** @param int<1, max> $id */
    public function findById(int $id): Store;

    public function findByKey(string $key): Store;

    public function insert(Store $store): void;

    public function update(Store $store): void;
}
