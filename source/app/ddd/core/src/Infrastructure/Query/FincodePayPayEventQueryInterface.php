<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type FincodePayPayEventItem = array{
 *     id: string,
 *     shop_id: string,
 *     access_id: string,
 *     order_id: string,
 *     amount: string,
 *     tax: string,
 *     customer_id: string,
 *     process_date: string,
 *     code_expiry_date: string,
 *     auth_max_date: string,
 *     code_id: string,
 *     payment_id: string,
 *     payment_date: string,
 *     error_code: string,
 *     pay_type: string,
 *     event: string,
 *     job_cd: string,
 *     status: string,
 * }
 */
interface FincodePayPayEventQueryInterface
{
    /** @psalm-return FincodePayPayEventItem|null */
    #[DbQuery('fincode_paypay_event_item_by_access_id', type: 'row')]
    public function itemByAccessId(string $accessId): array|null;
}
