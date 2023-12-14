<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type OrderLineItemItem = array{
 *     id: string,
 *     order_id: string,
 *     product_id: string,
 *     product_variant_id: string,
 *     title: string,
 *     maker_name: string,
 *     taxonomy_name: string,
 *     discount_price: string|null,
 *     original_price: string,
 *     original_tax: string,
 *     original_line_price: string,
 *     final_price: string,
 *     final_tax: string,
 *     final_line_price: string,
 *     tax_rate: string,
 *     quantity: string,
 * }
 */
interface OrderLineItemQueryInterface
{
    /**
     * @param int<1, max> $orderId
     *
     * @psalm-return list<OrderLineItemItem>
     */
    #[DbQuery('order_line_item_list_by_order_id')]
    public function listByOrderId(int $orderId): array;
}
