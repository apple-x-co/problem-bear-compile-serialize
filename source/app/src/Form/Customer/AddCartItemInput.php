<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Customer;

final class AddCartItemInput
{
    /**
     * @param int<1, max> $productId
     * @param int<1, max> $productVariantId
     * @param int<1, max> $quantity
     */
    public function __construct(
        public int $productId,
        public int $productVariantId,
        public int $quantity,
    ) {
    }
}
