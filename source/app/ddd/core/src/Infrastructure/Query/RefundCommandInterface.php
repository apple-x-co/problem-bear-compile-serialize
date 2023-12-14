<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface RefundCommandInterface
{
    /**
     * @param int<1, max> $orderId
     * @param int<0, max> $refundedAmount
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('refund_add')]
    public function add(
        int $orderId,
        int $refundedAmount,
        string $status,
    ): array;

    /** @param int<1, max> $id */
    #[DbQuery('refund_update')]
    public function update(int $id, string $status): void;
}
