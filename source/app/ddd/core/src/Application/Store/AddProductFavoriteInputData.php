<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

final class AddProductFavoriteInputData
{
    /**
     * @param int<1, max> $customerId
     * @param int<1, max> $productId
     */
    public function __construct(
        public readonly int $customerId,
        public readonly int $productId,
    ) {
    }
}
