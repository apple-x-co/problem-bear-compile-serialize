<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type PaymentIntentItem = array{
 *      id: string,
 *      order_id: string,
 *      billing_id: string,
 *      payment_method_id: string,
 *      idempotency_token: string,
 *      gateway: string,
 *      charge_amount: string,
 *      capture_amount: string,
 *      authorization: string|null,
 *      authorized_date: string|null,
 *      status: string,
 *  }
 */
interface PaymentIntentQueryInterface
{
    /**
     * @param int<1, max> $orderId
     *
     * @psalm-return PaymentIntentItem|null
     */
    #[DbQuery('payment_intent_item_by_order_id', type: 'row')]
    public function itemByOrderId(int $orderId): array|null;
}
