<?php

declare(strict_types=1);

namespace AppCore\Domain\Order;

interface OrderRepositoryInterface
{
    /** @param int<1, max> $id */
    public function findById(int $id): Order;

    public function insert(Order $order): void;

    public function update(Order $order): void;
}
