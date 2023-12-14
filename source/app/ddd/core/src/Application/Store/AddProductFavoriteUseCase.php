<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

use AppCore\Domain\CustomerFavoriteProduct\CustomerFavoriteProduct;
use AppCore\Domain\CustomerFavoriteProduct\CustomerFavoriteProductNotFoundException;
use AppCore\Domain\CustomerFavoriteProduct\CustomerFavoriteProductRepositoryInterface;
use AppCore\Domain\Product\ProductRepositoryInterface;
use DateTimeImmutable;

/** @SuppressWarnings(PHPMD.LongVariable) */
final class AddProductFavoriteUseCase
{
    public function __construct(
        private readonly CustomerFavoriteProductRepositoryInterface $customerFavoriteProductRepository,
        private readonly ProductRepositoryInterface $productRepository,
    ) {
    }

    public function execute(AddProductFavoriteInputData $inputData): void
    {
        try {
            $customerFavoriteProduct = $this->customerFavoriteProductRepository->findByUniqueKey(
                $inputData->customerId,
                $inputData->productId,
            );
        } catch (CustomerFavoriteProductNotFoundException) {
            $customerFavoriteProduct = null;
        }

        if ($customerFavoriteProduct !== null) {
            return;
        }

        $product = $this->productRepository->findById($inputData->productId);

        $customerFavoriteProduct = new CustomerFavoriteProduct(
            $inputData->customerId,
            $product->storeId,
            $inputData->productId,
            new DateTimeImmutable(),
        );

        $this->customerFavoriteProductRepository->insert($customerFavoriteProduct);
    }
}
