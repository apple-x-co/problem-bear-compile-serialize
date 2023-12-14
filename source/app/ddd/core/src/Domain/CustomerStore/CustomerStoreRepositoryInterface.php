<?php

declare(strict_types=1);

namespace AppCore\Domain\CustomerStore;

interface CustomerStoreRepositoryInterface
{
    /**
     * @param int<1, max> $customerId
     * @param int<1, max> $storeId
     */
    public function findByUniqueKey(int $customerId, int $storeId): CustomerStore;

    public function insert(CustomerStore $customerStore): void;

    public function update(CustomerStore $customerStore): void;
}
