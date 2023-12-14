<?php

declare(strict_types=1);

namespace AppCore\Domain\DiscountCode;

final class DiscountEntitledProduct
{
    /**
     * @param int<1, max> $productId
     * @param int<0, max> $id
     */
    public function __construct(
        public readonly int $productId,
        public readonly int $id = 0,
    ) {
    }

    /**
     * @param int<1, max> $productId
     * @param int<1, max> $id
     */
    public static function reconstruct(
        int $id,
        int $productId,
    ): DiscountEntitledProduct {
        return new self(
            $productId,
            $id,
        );
    }
}
