<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type StoreUsageBillingItem = array{
 *     id: string,
 *     store_usage_id: string,
 *     charge_amount: string,
 *     billing_date: string,
 *     scheduled_payment_date: string,
 *     due_date: string,
 *     paid_date: string,
 *     family_name: string,
 *     given_name: string,
 *     phonetic_family_name: string,
 *     phonetic_given_name: string,
 *     postal_code: string,
 *     state: string,
 *     city: string,
 *     address_line_1: string,
 *     address_line_2: string,
 *     phone_number: string,
 *     email: string,
 *     status: string,
 * }
 */
interface StoreUsageBillingQueryInterface
{
    /**
     * @param int<1, max> $storeUsageId
     *
     * @return StoreUsageBillingItem|null
     */
    #[DbQuery('store_usage_billing_item_by_store_usage_id', type: 'row')]
    public function itemByStoreUsageId(int $storeUsageId): array|null;

    /**
     * @param int<1, max> $storeId
     *
     * @return list<StoreUsageBillingItem>
     */
    #[DbQuery('store_usage_billing_list_by_store_id')]
    public function listByStoreId(int $storeId): array;
}
