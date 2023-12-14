<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface PaymentMethodCommandInterface
{
    /**
     * @param int<0, max> $fee
     * @param int<0, 1>   $available
     * @param int<1, max> $position
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('payment_method_add', 'row')]
    public function add(
        string $name,
        string $key,
        string $role,
        int $fee,
        int $available,
        int $position,
    ): array;

    /**
     * @param int<1, max> $id
     * @param int<0, max> $fee
     * @param int<0, 1>   $available
     * @param int<1, max> $position
     */
    #[DbQuery('payment_method_update', 'row')]
    public function update(
        int $id,
        string $name,
        int $fee,
        int $available,
        int $position,
    ): void;
}
