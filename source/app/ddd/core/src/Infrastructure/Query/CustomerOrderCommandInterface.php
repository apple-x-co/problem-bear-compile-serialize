<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface CustomerOrderCommandInterface
{
    /**
     * @param int<1, max> $customerId
     * @param int<1, max> $storeId
     * @param int<1, max> $orderId
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('customer_order_add', 'row')]
    public function add(
        int $customerId,
        int $storeId,
        int $orderId,
    ): array;
}
