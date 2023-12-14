<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Store\Common;

use AppCore\Application\Store\GetProductsInputData;
use AppCore\Application\Store\GetProductsUseCase;
use BEAR\Resource\Annotation\JsonSchema;
use BEAR\Resource\JsonRenderer;
use MyVendor\MyProject\Form\Customer\GetProductsInput;
use MyVendor\MyProject\Resource\Page\BaseStoreCommonApi;

use function array_map;
use function assert;

class Products extends BaseStoreCommonApi
{
    public function __construct(
        private readonly GetProductsUseCase $getProductsUseCase,
    ) {
    }

     #[JsonSchema(schema: 'store/common/get-products.json', params: 'store/common/get-products.json')]
    public function onGet(GetProductsInput $products): static
    {
        $this->renderer = new JsonRenderer();

//        $storeContext = $this->getStoreContext();

        $productIds = array_map(
            static function (array $product): int {
                $productId = (int) $product['id'];
                assert($productId > 0);

                return $productId;
            },
            $products->products,
        );

        $outputData = $this->getProductsUseCase->execute(
            new GetProductsInputData(
                1, //$storeContext->getStoreId(),
                $productIds,
            ),
        );

        $this->body['products'] = array_map(
            static function (array $item) {
                return [
                    'id' => $item['id'],
                    'title' => $item['title'],
                ];
            },
            $outputData->products,
        );

        return $this;
    }
}
