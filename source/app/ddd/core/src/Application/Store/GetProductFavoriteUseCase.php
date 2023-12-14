<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

use AppCore\Domain\CustomerFavoriteProduct\CustomerFavoriteProduct;
use AppCore\Domain\CustomerFavoriteProduct\CustomerFavoriteProductRepositoryInterface;

use function array_map;

/** @SuppressWarnings(PHPMD.LongVariable) */
final class GetProductFavoriteUseCase
{
    public function __construct(
        private readonly CustomerFavoriteProductRepositoryInterface $customerFavoriteProductRepository,
    ) {
    }

    public function execute(GetProductFavoriteInputData $inputData): GetProductFavoriteOutputData
    {
        $customerFavoriteProducts = $this->customerFavoriteProductRepository->findByStoreCustomer(
            $inputData->storeId,
            $inputData->customerId,
        );

        $productIds = array_map(
            static function (CustomerFavoriteProduct $item) {
                return $item->productId;
            },
            $customerFavoriteProducts,
        );

        return new GetProductFavoriteOutputData($productIds);
    }
}
