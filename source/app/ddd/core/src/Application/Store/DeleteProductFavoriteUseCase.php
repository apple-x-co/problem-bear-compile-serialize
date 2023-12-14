<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

use AppCore\Domain\CustomerFavoriteProduct\CustomerFavoriteProductRepositoryInterface;

/** @SuppressWarnings(PHPMD.LongVariable) */
final class DeleteProductFavoriteUseCase
{
    public function __construct(
        private readonly CustomerFavoriteProductRepositoryInterface $customerFavoriteProductRepository,
    ) {
    }

    public function execute(DeleteProductFavoriteInputData $inputData): void
    {
        $customerFavoriteProduct = $this->customerFavoriteProductRepository->findByUniqueKey(
            $inputData->customerId,
            $inputData->productId,
        );
        $this->customerFavoriteProductRepository->delete($customerFavoriteProduct);
    }
}
