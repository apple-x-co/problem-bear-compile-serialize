<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type BillingItem = array{
 *      id: string,
 *      order_id: string,
 *      billing_no: string,
 *      charge_amount: string,
 *      billing_date: string,
 *      family_name: string,
 *      given_name: string,
 *      phonetic_family_name: string,
 *      phonetic_given_name: string,
 *      postal_code: string,
 *      state: string,
 *      city: string,
 *      address_line_1: string,
 *      address_line_2: string,
 *      phone_number: string,
 *      email: string,
 * }
 */
interface BillingQueryInterface
{
    /**
     * @param int<1, max> $orderId
     *
     * @psalm-return BillingItem|null
     */
    #[DbQuery('billing_item_by_order_id')]
    public function itemByOrderId(int $orderId): array|null;
}
