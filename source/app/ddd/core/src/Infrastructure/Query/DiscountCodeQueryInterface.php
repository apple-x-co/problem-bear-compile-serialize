<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type DiscountCodeItem = array{
 *     id: string,
 *      store_id: string,
 *      title: string,
 *      code: string,
 *      type: string,
 *      value: string,
 *      start_date: string|null,
 *      end_date: string|null,
 *      usage_count: string,
 *      usage_limit: string|null,
 *      minimum_price: string|null,
 *      once_per_customer: string,
 *      target_selection: string,
 *      status: string,
 * }
 */
interface DiscountCodeQueryInterface
{
    /**
     * @param int<1, max> $storeId
     *
     * @psalm-return DiscountCodeItem|null
     */
    #[DbQuery('discount_code_item_by_unique_key', type: 'row')]
    public function itemUniqueKey(int $storeId, string $code): array|null;
}
