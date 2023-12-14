<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Store\Me\Cart;

use AppCore\Application\Store\AddCartItemInputData;
use AppCore\Application\Store\AddCartItemUserCase;
use AppCore\Application\Store\DeleteCartItemInputData;
use AppCore\Application\Store\DeleteCartItemUseCase;
use BEAR\Resource\Annotation\JsonSchema;
use BEAR\Resource\JsonRenderer;
use MyVendor\MyProject\Annotation\MeGuard;
use MyVendor\MyProject\Auth\CustomerAuthenticatorInterface;
use MyVendor\MyProject\Form\Customer\AddCartItemInput;
use MyVendor\MyProject\Form\Customer\DeleteCartItemInput;
use MyVendor\MyProject\Resource\Page\BaseStoreMeApi;

/** @SuppressWarnings(PHPMD.LongVariable) */
class Item extends BaseStoreMeApi
{
    public function __construct(
        private readonly AddCartItemUserCase $addCartItemUserCase,
        private readonly CustomerAuthenticatorInterface $authenticator,
        private readonly DeleteCartItemUseCase $deleteCartItemUseCase,
    ) {
    }

    #[MeGuard]
    #[JsonSchema('store/me/cart/add-item.json', params: 'store/me/cart/add-item.json')]
    public function onPost(AddCartItemInput $cartItem): static
    {
        $this->renderer = new JsonRenderer();

//        $storeContext = $this->getStoreContext();

        $this->addCartItemUserCase->execute(
            new AddCartItemInputData(
                $this->authenticator->getIdentity()->id,
                1, // $storeContext->getStoreId(),
                $cartItem->productId,
                $cartItem->productVariantId,
                $cartItem->quantity,
            ),
        );

        $this->body = [];

        return $this;
    }

    #[MeGuard]
    #[JsonSchema(schema: 'store/me/cart/delete-item.json', params: 'store/me/cart/delete-item.json')]
    public function onDelete(DeleteCartItemInput $cartItem): static
    {
        $this->renderer = new JsonRenderer();

        $this->deleteCartItemUseCase->execute(
            new DeleteCartItemInputData(
                $cartItem->productId,
                $cartItem->productVariantId,
                $cartItem->quantity,
            ),
        );

        $this->body = [];

        return $this;
    }
}
