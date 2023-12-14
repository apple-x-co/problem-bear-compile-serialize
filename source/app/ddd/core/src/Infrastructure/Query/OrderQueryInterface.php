<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type OrderItem = array{
 *     id: string,
 *     store_id: string,
 *     order_no: string,
 *     order_date: string,
 *     close_date: string|null,
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
 *     discount_code: string|null,
 *     discount_price: string|null,
 *     point_rate: string|null,
 *     spending_point: string|null,
 *     earning_point: string|null,
 *     total_price: string,
 *     total_tax: string,
 *     subtotal_price: string,
 *     payment_method_id: string,
 *     payment_method_name: string,
 *     payment_fee: string,
 *     note: string|null,
 *     order_note: string|null,
 *     pickup_status: string,
 *     status: string,
 * }
 */
interface OrderQueryInterface
{
    /**
     * @param int<1, max> $id
     *
     * @psalm-return OrderItem|null
     */
    #[DbQuery('order_item', type: 'row')]
    public function findById(int $id): array|null;
}
