<?php

declare(strict_types=1);

namespace AppCore\Domain\CustomerOrder;

interface CustomerOrderRepositoryInterface
{
    /** @param int<1, max> $orderId */
    public function findByOrderId(int $orderId): CustomerOrder;

    /**
     * @param int<1, max> $storeId
     * @param int<1, max> $customerId
     *
     * @return list<CustomerOrder>
     */
    public function findByStoreCustomer(int $storeId, int $customerId): array;

    public function insert(CustomerOrder $customerOrder): void;
}
