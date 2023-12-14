<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

use AppCore\Domain\Cart\CartItem;
use AppCore\Domain\Cart\CartStoreInterface;

use function array_map;
use function array_values;
use function iterator_to_array;

final class GetCartUseCase
{
    public function __construct(
        private readonly CartStoreInterface $cartStore,
    ) {
    }

    public function execute(GetCartInputData $inputData): GetCartOutputData
    {
        $cart = $this->cartStore->get();

        if ($cart->customerId !== $inputData->customerId) {
            return new GetCartOutputData($cart->storeId, []);
        }

        $items = array_map(
            static function (CartItem $item) {
                return [
                    'productId' => $item->productId,
                    'productVariantId' => $item->productVariantId,
                    'title' => $item->title,
                    'makerName' => $item->makerName,
                    'taxonomyName' => $item->taxonomyName,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'taxRate' => $item->taxRate,
                    'point' => $item->point,
                ];
            },
            iterator_to_array($cart->cartItems->getIterator()),
        );

        return new GetCartOutputData($cart->storeId, array_values($items));
    }
}
