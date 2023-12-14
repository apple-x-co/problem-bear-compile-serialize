<?php

declare(strict_types=1);

namespace AppCore\Domain\CustomerReward;

interface CustomerRewardRepositoryInterface
{
    /**
     * @param int<1, max> $customerId
     * @param int<1, max> $storeId
     */
    public function findByUniqueKey(int $customerId, int $storeId): CustomerReward;

    public function insert(CustomerReward $customerReward): void;

    public function update(CustomerReward $customerReward): void;
}
