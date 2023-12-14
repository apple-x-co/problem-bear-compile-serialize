<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\PopularProduct\PopularProduct;
use AppCore\Domain\PopularProduct\PopularProductRepositoryInterface;
use AppCore\Infrastructure\Query\PopularProductCommandInterface;
use AppCore\Infrastructure\Query\PopularProductQueryInterface;

use function array_map;
use function assert;

/** @psalm-import-type PopularProductItem from PopularProductQueryInterface */
class PopularProductRepository implements PopularProductRepositoryInterface
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        private readonly PopularProductCommandInterface $popularProductCommand,
        private readonly PopularProductQueryInterface $popularProductQuery,
    ) {
    }

    /**
     * @param int<1, max> $storeId
     *
     * @return list<PopularProduct>
     */
    public function findByStoreId(int $storeId): array
    {
        $items = $this->popularProductQuery->listByStoreId($storeId);

        return array_map(
            fn (array $item) => $this->toModel($item),
            $items,
        );
    }

    public function insert(PopularProduct $popularProduct): void
    {
        $result = $this->popularProductCommand->add(
            $popularProduct->storeId,
            $popularProduct->productId,
            $popularProduct->position,
        );

        $popularProduct->setNewId($result['id']);
    }

    public function update(PopularProduct $popularProduct): void
    {
        if (empty($popularProduct->id)) {
            return;
        }

        $this->popularProductCommand->update(
            $popularProduct->id,
            $popularProduct->position,
        );
    }

    /**
     * @psalm-param PopularProductItem $item
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    // phpcs:ignore Squiz.Commenting.FunctionComment.MissingParamName
    private function toModel(array $item): PopularProduct
    {
        $id = (int) $item['id'];
        assert($id > 0);

        $storeId = (int) $item['store_id'];
        assert($storeId > 0);

        $productId = (int) $item['product_id'];
        assert($productId > 0);

        $position = (int) $item['position'];
        assert($position > 0);

        return PopularProduct::reconstruct(
            $id,
            $storeId,
            $productId,
            $position,
        );
    }
}
