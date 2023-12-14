<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Store\Me;

use AppCore\Application\Store\GetCartInputData;
use AppCore\Application\Store\GetCartUseCase;
use AppCore\Domain\Cart\CartNotFoundException;
use BEAR\Resource\Annotation\JsonSchema;
use BEAR\Resource\JsonRenderer;
use MyVendor\MyProject\Annotation\MeGuard;
use MyVendor\MyProject\Auth\CustomerAuthenticatorInterface;
use MyVendor\MyProject\Resource\Page\BaseStoreMeApi;

use function array_map;

class Cart extends BaseStoreMeApi
{
    public function __construct(
        private readonly CustomerAuthenticatorInterface $authenticator,
        private readonly GetCartUseCase $getCartUseCase,
    ) {
    }

    #[MeGuard]
    #[JsonSchema('store/me/get-cart.json')]
    public function onGet(): static
    {
        $this->renderer = new JsonRenderer();

        try {
            $outputData = $this->getCartUseCase->execute(
                new GetCartInputData(
                    $this->authenticator->getIdentity()->id,
                ),
            );
        } catch (CartNotFoundException) {
            $this->body['cart'] = null;

            return $this;
        }

        $items = array_map(
            static function (array $item) {
                return [
                    'productId' => $item['productId'],
                    'productVariantId' => $item['productVariantId'],
                    'title' => $item['title'],
                    'makerName' => $item['makerName'],
                    'taxonomyName' => $item['taxonomyName'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'taxRate' => $item['taxRate'],
                    'point' => $item['point'],
                ];
            },
            $outputData->items,
        );

        $this->body['cart'] = [
            'storeId' => $outputData->storeId,
            'items' => $items,
        ];

        return $this;
    }
}
