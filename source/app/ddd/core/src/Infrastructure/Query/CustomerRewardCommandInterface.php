<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface CustomerRewardCommandInterface
{
    /**
     * @param int<1, max> $customerId
     * @param int<1, max> $storeId
     * @param int<0, max> $remainingPoint
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('customer_reward_add', 'row')]
    public function add(
        int $customerId,
        int $storeId,
        int $remainingPoint,
    ): array;

    /**
     * @param int<1, max> $id
     * @param int<0, max> $remainingPoint
     */
    #[DbQuery('customer_reward_update', 'row')]
    public function update(
        int $id,
        int $remainingPoint,
    ): void;
}
