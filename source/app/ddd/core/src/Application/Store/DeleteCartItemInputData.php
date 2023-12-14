<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

final class DeleteCartItemInputData
{
    /**
     * @param int<1, max> $productId
     * @param int<1, max> $productVariantId
     * @param int<1, max> $quantity
     */
    public function __construct(
        public readonly int $productId,
        public readonly int $productVariantId,
        public readonly int $quantity,
    ) {
    }
}
