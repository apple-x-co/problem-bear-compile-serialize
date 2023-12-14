<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\FeaturedProduct\FeaturedProduct;
use AppCore\Domain\FeaturedProduct\FeaturedProductRepositoryInterface;
use AppCore\Infrastructure\Query\FeaturedProductCommandInterface;
use AppCore\Infrastructure\Query\FeaturedProductQueryInterface;

use function array_map;
use function assert;

/** @psalm-import-type FeaturedProductItem from FeaturedProductQueryInterface */
class FeaturedProductRepository implements FeaturedProductRepositoryInterface
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        private readonly FeaturedProductCommandInterface $featuredProductCommand,
        private readonly FeaturedProductQueryInterface $featuredProductQuery,
    ) {
    }

    /**
     * @param int<1, max> $storeId
     *
     * @return list<FeaturedProduct>
     */
    public function findByStoreId(int $storeId): array
    {
        $items = $this->featuredProductQuery->listByStoreId($storeId);

        return array_map(
            fn (array $item) => $this->toModel($item),
            $items,
        );
    }

    public function insert(FeaturedProduct $featuredProduct): void
    {
        $result = $this->featuredProductCommand->add(
            $featuredProduct->storeId,
            $featuredProduct->productId,
            $featuredProduct->position,
        );

        $featuredProduct->setNewId($result['id']);
    }

    public function update(FeaturedProduct $featuredProduct): void
    {
        if (empty($featuredProduct->id)) {
            return;
        }

        $this->featuredProductCommand->update(
            $featuredProduct->id,
            $featuredProduct->position,
        );
    }

    /**
     * @psalm-param FeaturedProductItem $item
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    // phpcs:ignore Squiz.Commenting.FunctionComment.MissingParamName
    private function toModel(array $item): FeaturedProduct
    {
        $id = (int) $item['id'];
        assert($id > 0);

        $storeId = (int) $item['store_id'];
        assert($storeId > 0);

        $productId = (int) $item['product_id'];
        assert($productId > 0);

        $position = (int) $item['position'];
        assert($position > 0);

        return FeaturedProduct::reconstruct(
            $id,
            $storeId,
            $productId,
            $position,
        );
    }
}
