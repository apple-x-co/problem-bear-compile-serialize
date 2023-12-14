<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface StockCommandInterface
{
    /**
     * @param int<1, max> $storeId
     * @param int<1, max> $productVariantId
     * @param int<0, max> $quantity
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('stock_add', type: 'row')]
    public function add(
        int $storeId,
        int $productVariantId,
        string $idempotencyToken,
        int $quantity,
    ): array;

    /**
     * @param int<1, max> $id
     * @param int<0, max> $quantity
     */
    #[DbQuery('stock_update', type: 'row')]
    public function update(
        int $id,
        string $idempotencyToken,
        int $quantity,
    ): void;

    /** @param int<1, max> $id */
    #[DbQuery('stock_delete', type: 'row')]
    public function delete(int $id): void;
}
