<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Store\Me\Products;

use AppCore\Application\Store\AddProductFavoriteInputData;
use AppCore\Application\Store\AddProductFavoriteUseCase;
use AppCore\Application\Store\DeleteProductFavoriteInputData;
use AppCore\Application\Store\DeleteProductFavoriteUseCase;
use AppCore\Application\Store\GetProductFavoriteInputData;
use AppCore\Application\Store\GetProductFavoriteUseCase;
use BEAR\Resource\Annotation\JsonSchema;
use BEAR\Resource\JsonRenderer;
use MyVendor\MyProject\Annotation\MeGuard;
use MyVendor\MyProject\Auth\CustomerAuthenticatorInterface;
use MyVendor\MyProject\Form\Customer\AddFavoriteProductInput;
use MyVendor\MyProject\Form\Customer\DeleteFavoriteProductInput;
use MyVendor\MyProject\Resource\Page\BaseStoreMeApi;

/** @SuppressWarnings(PHPMD.LongVariable) */
class Favorites extends BaseStoreMeApi
{
    public function __construct(
        private readonly AddProductFavoriteUseCase $addProductFavoriteUseCase,
        private readonly CustomerAuthenticatorInterface $authenticator,
        private readonly DeleteProductFavoriteUseCase $deleteProductFavoriteUseCase,
        private readonly GetProductFavoriteUseCase $getProductFavoriteUseCase,
    ) {
    }

    #[MeGuard]
    #[JsonSchema(schema: 'store/me/products/get-favorites.json')]
    public function onGet(): static
    {
        $this->renderer = new JsonRenderer();

//        $storeContext = $this->getStoreContext();

        $outputData = $this->getProductFavoriteUseCase->execute(
            new GetProductFavoriteInputData(
                1, // $storeContext->getStoreId(),
                $this->authenticator->getIdentity()->id,
            ),
        );

        $this->body['products'] = $outputData->productIds;

        return $this;
    }

    #[MeGuard]
    #[JsonSchema(schema: 'store/me/products/post-favorites.json', params: 'store/me/products/post-favorites.json')]
    public function onPost(AddFavoriteProductInput $product): static
    {
        $this->renderer = new JsonRenderer();

        $this->addProductFavoriteUseCase->execute(
            new AddProductFavoriteInputData(
                $this->authenticator->getIdentity()->id,
                $product->productId,
            ),
        );

        $this->body = [];

        return $this;
    }

    #[MeGuard]
    #[JsonSchema(schema: 'store/me/products/delete-favorites.json', params: 'store/me/products/delete-favorites.json')]
    public function onDelete(DeleteFavoriteProductInput $product): static
    {
        $this->renderer = new JsonRenderer();

        $this->deleteProductFavoriteUseCase->execute(
            new DeleteProductFavoriteInputData(
                $this->authenticator->getIdentity()->id,
                $product->productId,
            ),
        );

        $this->body = [];

        return $this;
    }
}
