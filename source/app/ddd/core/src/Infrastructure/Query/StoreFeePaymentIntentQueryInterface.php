<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type StoreFeePaymentIntentItem = array{
 *     id: string,
 *     store_usage_billing_id: string,
 *     payment_method_id: string,
 *     idempotency_token: string,
 *     gateway: string,
 *     charge_amount: string,
 *     capture_amount: string,
 *     refunded_amount: string,
 *     authorization: string|null,
 *     authorized_date: string|null,
 *     cancel_date: string|null,
 *     status: string,
 * }
 */
interface StoreFeePaymentIntentQueryInterface
{
    /**
     * @param int<1, max> $id
     *
     * @return StoreFeePaymentIntentItem|null
     */
    #[DbQuery('store_fee_payment_intent_item', type: 'row')]
    public function item(int $id): array|null;

    /**
     * @param int<1, max> $storeId
     *
     * @return list<StoreFeePaymentIntentItem>
     */
    #[DbQuery('store_fee_payment_intent_list_by_store_id')]
    public function listByStoreId(int $storeId): array;

    /**
     * @param int<1, max> $storeUsageId
     *
     * @return list<StoreFeePaymentIntentItem>
     */
    #[DbQuery('store_fee_payment_intent_list_by_store_usage_id')]
    public function listByStoreUsageId(int $storeUsageId): array;
}
