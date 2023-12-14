<?php

declare(strict_types=1);

namespace AppCore\Domain\Order;

final class OrderLineItem
{
    /**
     * @param int<1, max>      $productId
     * @param int<1, max>      $productVariantId
     * @param int<min, 0>|null $discountPrice
     * @param int<0, max>      $originalPrice
     * @param int<0, max>      $originalTax
     * @param int<0, max>      $originalLinePrice
     * @param int<0, max>      $finalPrice
     * @param int<0, max>      $finalTax
     * @param int<0, max>      $finalLinePrice
     * @param int<0, max>      $taxRate
     * @param int<1, max>      $quantity
     * @param int<0, max>      $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        public readonly int $productId,
        public readonly int $productVariantId,
        public readonly string $title,
        public readonly string $makerName,
        public readonly string $taxonomyName,
        public readonly int|null $discountPrice,
        public readonly int $originalPrice,
        public readonly int $originalTax,
        public readonly int $originalLinePrice,
        public readonly int $finalPrice,
        public readonly int $finalTax,
        public readonly int $finalLinePrice,
        public readonly int $taxRate,
        public readonly int $quantity,
        public readonly int $id = 0,
    ) {
    }

    /**
     * @param int<1, max>      $productId
     * @param int<1, max>      $productVariantId
     * @param int<min, 0>|null $discountPrice
     * @param int<0, max>      $originalPrice
     * @param int<0, max>      $originalTax
     * @param int<0, max>      $originalLinePrice
     * @param int<0, max>      $finalPrice
     * @param int<0, max>      $finalTax
     * @param int<0, max>      $finalLinePrice
     * @param int<0, max>      $taxRate
     * @param int<1, max>      $quantity
     * @param int<1, max>      $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public static function reconstruct(
        int $id,
        int $productId,
        int $productVariantId,
        string $title,
        string $makerName,
        string $taxonomyName,
        int|null $discountPrice,
        int $originalPrice,
        int $originalTax,
        int $originalLinePrice,
        int $finalPrice,
        int $finalTax,
        int $finalLinePrice,
        int $taxRate,
        int $quantity,
    ): OrderLineItem {
        return new self(
            $productId,
            $productVariantId,
            $title,
            $makerName,
            $taxonomyName,
            $discountPrice,
            $originalPrice,
            $originalTax,
            $originalLinePrice,
            $finalPrice,
            $finalTax,
            $finalLinePrice,
            $taxRate,
            $quantity,
            $id,
        );
    }

    // TODO: add method
}
