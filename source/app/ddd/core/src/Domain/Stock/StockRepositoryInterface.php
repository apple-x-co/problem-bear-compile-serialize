<?php

declare(strict_types=1);

namespace AppCore\Domain\Stock;

interface StockRepositoryInterface
{
    /**
     * @param int<1, max> $storeId
     *
     * @return list<Stock>
     */
    public function findByStoreId(int $storeId): array;

    public function insert(Stock $stock): void;

    public function update(Stock $stock): void;

    public function delete(Stock $stock): void;
}
