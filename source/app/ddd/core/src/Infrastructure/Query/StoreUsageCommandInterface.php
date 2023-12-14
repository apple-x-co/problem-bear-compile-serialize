<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface StoreUsageCommandInterface
{
    /**
     * @param int<1, max> $storeId
     * @param int<1, max> $totalPrice
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('store_usage_add', type: 'row')]
    public function add(
        int $storeId,
        string $description,
        DateTimeImmutable $targetDate,
        int $totalPrice,
        string $status,
    ): array;

    /** @param int<1, max> $id */
    #[DbQuery('store_usage_update', type: 'row')]
    public function update(int $id, string $status): void;
}
