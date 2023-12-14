<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

use AppCore\Domain\Cart\CartStoreInterface;

final class DeleteCartItemUseCase
{
    public function __construct(
        private readonly CartStoreInterface $cartStore,
    ) {
    }

    public function execute(DeleteCartItemInputData $inputData): void
    {
        $cart = $this->cartStore->get();

        $cart = $cart->removeItem(
            $inputData->productId,
            $inputData->productVariantId,
            $inputData->quantity,
        );

        $this->cartStore->update($cart);
    }
}
