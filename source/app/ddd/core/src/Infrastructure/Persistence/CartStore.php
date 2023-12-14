<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\Cart\Cart;
use AppCore\Domain\Cart\CartItem;
use AppCore\Domain\Cart\CartItems;
use AppCore\Domain\Cart\CartNotFoundException;
use AppCore\Domain\Cart\CartStoreInterface;
use Aura\Session\Session as AuraSession;

use function array_map;
use function is_string;
use function json_decode;
use function json_encode;

/** @SuppressWarnings(PHPMD.StaticAccess) */
final class CartStore implements CartStoreInterface
{
    private const SEGMENT = 'App\Session\Customer\Cart';

    public function __construct(private readonly AuraSession $session)
    {
    }

    public function get(): Cart
    {
        $segment = $this->session->getSegment(self::SEGMENT);

        $json = $segment->get('cart');
        if (! is_string($json)) {
            throw new CartNotFoundException();
        }

        /**
         * @var array{
         *     version?: int,
         *     token: string|null,
         *     customerId: int<1, max>|null,
         *     storeId: int<1, max>,
         *     cartItems: list<array{
         *         productId: int<1, max>,
         *         productVariantId: int<1, max>,
         *         title: string,
         *         makerName: string,
         *         taxonomyName: string,
         *         quantity: int<1, max>,
         *         price: int<1, max>,
         *         taxRate: int<1, 100>,
         *         point: int<0, max>|null,
         *     }>,
         *     note: string|null,
         * } $sessionCart
         */
        $sessionCart = json_decode($json, true);

        $version = $sessionCart['version'] ?? null;
        if ($version !== Cart::DATA_VERSION) {
            throw new CartNotFoundException();
        }

        return Cart::reconstruct(
            $sessionCart['token'],
            $sessionCart['customerId'],
            $sessionCart['storeId'],
            new CartItems(
                array_map(
                    static function (array $item) {
                        return CartItem::reconstruct(
                            $item['productId'],
                            $item['productVariantId'],
                            $item['title'],
                            $item['makerName'],
                            $item['taxonomyName'],
                            $item['quantity'],
                            $item['price'],
                            $item['taxRate'],
                            $item['point'],
                        );
                    },
                    $sessionCart['cartItems'],
                ),
            ),
            $sessionCart['note'],
        );
    }

    public function update(Cart $cart): void
    {
        $segment = $this->session->getSegment(self::SEGMENT);
        $segment->set('cart', json_encode($cart));
    }

    public function delete(Cart $cart): void
    {
        $segment = $this->session->getSegment(self::SEGMENT);
        $segment->clear();
    }
}
