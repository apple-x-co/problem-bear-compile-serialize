<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type CustomerPointItem = array{
 *     id: string,
 *      customer_reward_id: string,
 *      uuid: string,
 *      type: string,
 *      transaction_date: string,
 *      expire_date: string,
 *      point: string,
 *      remaining_point: string,
 *      invalidation_date: string|null,
 *      invalidation_reason: string|null,
 * }
 */
interface CustomerPointQueryInterface
{
    /**
     * @param int<1, max> $customerRewardId
     *
     * @psalm-return list<CustomerPointItem>
     */
    #[DbQuery('customer_point_list_by_customer_reward_id')]
    public function listByCustomerRewardId(int $customerRewardId): array;
}
