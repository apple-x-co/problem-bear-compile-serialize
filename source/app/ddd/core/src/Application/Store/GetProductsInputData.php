<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

final class GetProductsInputData
{
    /**
     * @param int<1, max>       $storeId
     * @param list<int<1, max>> $productIds
     */
    public function __construct(
        public readonly int $storeId,
        public readonly array $productIds = [],
    ) {
    }
}
