<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\ProductShopStock\ProductShopStock;
use AppCore\Domain\ProductShopStock\ProductShopStockRepositoryInterface;
use AppCore\Domain\ProductShopStock\ProductShopStockStatus;
use AppCore\Infrastructure\Query\ProductShopStockCommandInterface;
use AppCore\Infrastructure\Query\ProductShopStockQueryInterface;

use function array_map;
use function assert;

/** @psalm-import-type ProductShopStockItem from ProductShopStockQueryInterface */
class ProductShopStockRepository implements ProductShopStockRepositoryInterface
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        private readonly ProductShopStockCommandInterface $productShopStockCommand,
        private readonly ProductShopStockQueryInterface $productShopStockQuery,
    ) {
    }

    /**
     * @param int<1, max> $productId
     *
     * @return list<ProductShopStock>
     */
    public function findByProductId(int $productId): array
    {
        $items = $this->productShopStockQuery->listByProductId($productId);

        return array_map(
            fn (array $item): ProductShopStock => $this->toModel($item),
            $items,
        );
    }

    public function insert(ProductShopStock $productShopStock): void
    {
        $result = $this->productShopStockCommand->add(
            $productShopStock->productId,
            $productShopStock->shopId,
            $productShopStock->status->value,
        );

        $productShopStock->setNewId($result['id']);
    }

    /**
     * @psalm-param ProductShopStockItem $item
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    // phpcs:ignore Squiz.Commenting.FunctionComment.MissingParamName
    private function toModel(array $item): ProductShopStock
    {
        $id = (int) $item['id'];
        assert($id > 0);

        $productId = (int) $item['product_id'];
        assert($productId > 0);

        $shopId = (int) $item['shop_id'];
        assert($shopId > 0);

        return ProductShopStock::reconstruct(
            $id,
            $productId,
            ProductShopStockStatus::from($item['status']),
            $shopId,
        );
    }
}
