<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

use AppCore\Domain\Product\Product;
use AppCore\Domain\Product\ProductRepositoryInterface;

use function array_map;
use function assert;

final class GetProductsUseCase
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
    ) {
    }

    public function execute(GetProductsInputData $inputData): GetProductsOutputData
    {
        $products = $this->productRepository->findByStoreIdAndProductIds(
            $inputData->storeId,
            $inputData->productIds,
        );

        return new GetProductsOutputData(
            array_map(
                static function (Product $item) {
                    assert($item->id > 0);

                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                    ];
                },
                $products,
            ),
        );
    }
}
