<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

final class GetProductFavoriteInputData
{
    /**
     * @param int<1, max> $storeId
     * @param int<1, max> $customerId
     */
    public function __construct(
        public readonly int $storeId,
        public readonly int $customerId,
    ) {
    }
}
