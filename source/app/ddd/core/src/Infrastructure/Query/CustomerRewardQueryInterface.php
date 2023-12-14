<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type CustomerRewardItem = array{
 *     id: string,
 *      customer_id: string,
 *      store_id: string,
 *      remaining_point: string,
 * }
 */
interface CustomerRewardQueryInterface
{
    /**
     * @param int<1, max> $customerId
     * @param int<1, max> $storeId
     *
     * @psalm-return CustomerRewardItem|null
     */
    #[DbQuery('customer_reward_item_by_unique_key', type: 'row')]
    public function itemByUniqueKey(int $customerId, int $storeId): array|null;
}
