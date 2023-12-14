<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

use AppCore\Domain\Cart\Cart;
use AppCore\Domain\Cart\CartNotFoundException;
use AppCore\Domain\Cart\CartStoreInterface;

final class AddCartItemUserCase
{
    public function __construct(
        private readonly CartStoreInterface $cartStore,
    ) {
    }

    public function execute(AddCartItemInputData $inputData): void
    {
        try {
            $cart = $this->cartStore->get();
        } catch (CartNotFoundException) {
            $cart = new Cart(
                null,
                $inputData->customerId,
                $inputData->storeId,
            );
        }

        // TODO: 商品情報を取得する

        $cart = $cart->addItem(
            $inputData->productId,
            $inputData->productVariantId,
            'HELLO', // DUMMY
            'WORLD', // DUMMY
            'BEAR', // DUMMY
            $inputData->quantity,
            1000, // DUMMY
            10, // DUMMY
        );
        $this->cartStore->update($cart);
    }
}
