<?php

declare(strict_types=1);

namespace AppCore\Domain\Refund;

interface RefundRepositoryInterface
{
    /** @param int<1, max> $orderId */
    public function findByOrderId(int $orderId): Refund;

    public function insert(Refund $refund): void;

    public function update(Refund $refund): void;
}
