<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\Stock\Stock;
use AppCore\Domain\Stock\StockRepositoryInterface;
use AppCore\Infrastructure\Query\StockCommandInterface;
use AppCore\Infrastructure\Query\StockQueryInterface;

use function array_map;
use function assert;

/**
 * @psalm-import-type StockItem from StockQueryInterface
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class StockRepository implements StockRepositoryInterface
{
    public function __construct(
        private readonly StockCommandInterface $stockCommand,
        private readonly StockQueryInterface $stockQuery,
    ) {
    }

    /**
     * @param int<1, max> $storeId
     *
     * @return list<Stock>
     */
    public function findByStoreId(int $storeId): array
    {
        $items = $this->stockQuery->listByStoreId($storeId);

        return array_map(
            fn (array $item) => $this->toModel($item),
            $items,
        );
    }

    /** @psalm-param StockItem $stockItem */
    private function toModel(array $stockItem): Stock
    {
        $id = (int) $stockItem['id'];
        assert($id > 0);

        $storeId = (int) $stockItem['store_id'];
        assert($storeId > 0);

        $productVariantId = (int) $stockItem['product_variant_id'];
        assert($productVariantId > 0);

        $quantity = (int) $stockItem['quantity'];
        assert($quantity > -1);

        return Stock::reconstruct(
            $id,
            $storeId,
            $productVariantId,
            $stockItem['idempotency_token'],
            $quantity,
        );
    }

    public function insert(Stock $stock): void
    {
        $result = $this->stockCommand->add(
            $stock->storeId,
            $stock->productVariantId,
            $stock->idempotencyToken,
            $stock->quantity,
        );

        $stock->setNewId($result['id']);
    }

    public function update(Stock $stock): void
    {
        if (empty($stock->id)) {
            return;
        }

        $this->stockCommand->update(
            $stock->id,
            $stock->idempotencyToken,
            $stock->quantity,
        );
    }

    public function delete(Stock $stock): void
    {
        if (empty($stock->id)) {
            return;
        }

        $this->stockCommand->delete($stock->id);
    }
}
