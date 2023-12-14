<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type RefundItem = array{
 *      id: string,
 *      order_id: string,
 *      refunded_amount: string,
 *      status: string
 *  }
 */
interface RefundQueryInterface
{
    /**
     * @param int<1, max> $orderId
     *
     * @psalm-return RefundItem|null
     */
    #[DbQuery('refund_item_by_order_id')]
    public function itemByOrderId(int $orderId): array|null;
}
