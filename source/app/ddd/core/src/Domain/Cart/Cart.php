<?php

declare(strict_types=1);

namespace AppCore\Domain\Cart;

use JsonSerializable;

use function iterator_to_array;

final class Cart implements JsonSerializable
{
    public const DATA_VERSION = 1;

    /**
     * @param int<1, max>|null $customerId
     * @param int<1, max>      $storeId
     */
    public function __construct(
        public readonly string|null $token,
        public readonly int|null $customerId,
        public readonly int $storeId,
        public readonly CartItems $cartItems = new CartItems([]),
        public readonly string|null $note = null,
    ) {
        if ($this->token === null && $this->customerId === null) {
            throw new InvalidCartException();
        }
    }

    /**
     * @param int<1, max>|null $customerId
     * @param int<1, max>      $storeId
     */
    public static function reconstruct(
        string|null $token,
        int|null $customerId,
        int $storeId,
        CartItems $cartItems,
        string|null $note,
    ): Cart {
        return new self(
            $token,
            $customerId,
            $storeId,
            $cartItems,
            $note,
        );
    }

    /**
     * @param int<1, max> $productId
     * @param int<1, max> $productVariantId
     * @param int<1, max> $quantity
     * @param int<1, max> $price
     * @param int<1, 100> $taxRate
     */
    public function addItem(
        int $productId,
        int $productVariantId,
        string $title,
        string $makerName,
        string $taxonomyName,
        int $quantity,
        int $price,
        int $taxRate,
    ): self {
        return new self(
            $this->token,
            $this->customerId,
            $this->storeId,
            $this->cartItems->add(
                new CartItem(
                    $productId,
                    $productVariantId,
                    $title,
                    $makerName,
                    $taxonomyName,
                    $quantity,
                    $price,
                    $taxRate,
                    null,
                ),
            ),
            $this->note,
        );
    }

    /**
     * @param int<1, max> $productId
     * @param int<1, max> $productVariantId
     */
    public function removeItem(
        int $productId,
        int $productVariantId,
        int $quantity,
    ): self {
        $target = null;

        foreach ($this->cartItems->getIterator() as $cartItem) {
            if (
                $cartItem->productId === $productId &&
                $cartItem->productVariantId === $productVariantId
            ) {
                $target = $cartItem;

                break;
            }
        }

        return new self(
            $this->token,
            $this->customerId,
            $this->storeId,
            $target === null ? clone $this->cartItems : $this->cartItems->remove($target, $quantity),
            $this->note,
        );
    }

    public function jsonSerialize(): mixed
    {
        // CartStore で使う
        return [
            'version' => self::DATA_VERSION,
            'token' => $this->token,
            'customerId' => $this->customerId,
            'storeId' => $this->storeId,
            'cartItems' => iterator_to_array($this->cartItems->getIterator()),
            'note' => $this->note,
        ];
    }
}
