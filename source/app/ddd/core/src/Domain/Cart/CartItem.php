<?php

declare(strict_types=1);

namespace AppCore\Domain\Cart;

use JsonSerializable;

use function round;

final class CartItem implements JsonSerializable
{
    /**
     * @param int<1, max>      $productId
     * @param int<1, max>      $productVariantId
     * @param int<1, max>      $quantity
     * @param int<1, max>      $price
     * @param int<1, 100>      $taxRate
     * @param int<0, max>|null $point
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        public readonly int $productId,
        public readonly int $productVariantId,
        public readonly string $title,
        public readonly string $makerName,
        public readonly string $taxonomyName,
        public readonly int $quantity,
        public readonly int $price,
        public readonly int $taxRate,
        public readonly int|null $point,
    ) {
    }

    /**
     * @param int<1, max>      $productId
     * @param int<1, max>      $productVariantId
     * @param int<1, max>      $quantity
     * @param int<1, max>      $price
     * @param int<1, 100>      $taxRate
     * @param int<0, max>|null $point
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public static function reconstruct(
        int $productId,
        int $productVariantId,
        string $title,
        string $makerName,
        string $taxonomyName,
        int $quantity,
        int $price,
        int $taxRate,
        int|null $point,
    ): CartItem {
        return new self(
            $productId,
            $productVariantId,
            $title,
            $makerName,
            $taxonomyName,
            $quantity,
            $price,
            $taxRate,
            $point,
        );
    }

    /** @param int<1, max> $quantity */
    public function changeQuantity(int $quantity): self
    {
        return new self(
            $this->productId,
            $this->productVariantId,
            $this->title,
            $this->makerName,
            $this->taxonomyName,
            $quantity,
            $this->price,
            $this->taxRate,
            $this->point,
        );
    }

    private function calculateTax(): int
    {
        return (int) round($this->price * $this->taxRate / 100);
    }

    public function calculateItemPrice(): int
    {
        return ($this->price + $this->calculateTax()) * $this->quantity;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'productId' => $this->productId,
            'productVariantId' => $this->productVariantId,
            'title' => $this->title,
            'makerName' => $this->makerName,
            'taxonomyName' => $this->taxonomyName,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'taxRate' => $this->taxRate,
            'point' => $this->point,
        ];
    }
}
