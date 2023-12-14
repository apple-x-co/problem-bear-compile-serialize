<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Store\Me\Products;

use AppCore\Presentation\Shared\EncryptCookiesInterface;
use BEAR\Resource\Annotation\JsonSchema;
use BEAR\Resource\JsonRenderer;
use MyVendor\MyProject\Form\Customer\AddViewProductInput;
use MyVendor\MyProject\Resource\Page\BaseStoreMeApi;

use function array_map;
use function array_unshift;
use function explode;
use function implode;
use function in_array;

class Views extends BaseStoreMeApi
{
    public function __construct(
        private readonly EncryptCookiesInterface $encryptCookies,
    ) {
    }

    #[JsonSchema(schema: 'store/me/products/get-views.json')]
    public function onGet(): static
    {
        $this->renderer = new JsonRenderer();

        $raw = $this->encryptCookies->get('views');
        $views = $raw === null ? [] : explode(',', $raw);

        $this->body['products'] = array_map(
            static fn (string $productId) => (int) $productId,
            $views,
        );

        return $this;
    }

    #[JsonSchema(schema: 'store/me/products/post-views.json', params: 'store/me/products/post-views.json')]
    public function onPost(AddViewProductInput $product): static
    {
        $this->renderer = new JsonRenderer();

        $raw = $this->encryptCookies->get('views');
        $views = $raw === null ? [] : explode(',', $raw);
        if (! in_array((string) $product->productId, $views, true)) {
            array_unshift($views, $product->productId);
        }

        $this->encryptCookies->set(
            'views',
            implode(',', $views),
            2,
        );

        $this->body = [];

        return $this;
    }

    public function onDelete(): static
    {
        $this->renderer = new JsonRenderer();

        $this->encryptCookies->revoke('views');

        $this->body = [];

        return $this;
    }
}
