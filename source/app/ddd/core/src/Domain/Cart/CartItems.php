<?php

declare(strict_types=1);

namespace AppCore\Domain\Cart;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

use function array_map;
use function array_reduce;
use function array_values;
use function assert;

/** @template-implements IteratorAggregate<CartItem> */
final class CartItems implements IteratorAggregate
{
    /** @param list<CartItem> $values */
    public function __construct(
        private readonly array $values,
    ) {
    }

    /** @return Traversable<CartItem> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }

    public function add(CartItem $cartItem): self
    {
        $isNew = true;

        $values = array_map(
            static function (CartItem $item) use (&$isNew, $cartItem) {
                if (
                    $item->productId === $cartItem->productId &&
                    $item->productVariantId === $cartItem->productVariantId
                ) {
                    $isNew = false;

                    return $item->changeQuantity($item->quantity + $cartItem->quantity);
                }

                return clone $item;
            },
            $this->values,
        );

        if ($isNew) {
            $values[] = $cartItem;
        }

        return new self($values);
    }

    public function remove(CartItem $cartItem, int $quantity): self
    {
        $values = array_reduce(
            $this->values,
            static function (array $carry, CartItem $item) use ($cartItem, $quantity) {
                if (
                    ! (
                        $item->productId === $cartItem->productId &&
                        $item->productVariantId === $cartItem->productVariantId
                    )
                ) {
                    $carry[] = $item;

                    return $carry;
                }

                if ($item->quantity > $quantity) {
                    $newQuantity = $item->quantity - $quantity;
                    assert($newQuantity > 0);
                    $item = $item->changeQuantity($newQuantity);
                    $carry[] = $item;
                }

                return $carry;
            },
            [],
        );

        return new self(array_values($values));
    }
}
