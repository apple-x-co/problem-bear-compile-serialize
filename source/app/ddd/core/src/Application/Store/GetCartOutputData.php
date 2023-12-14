<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

final class GetCartOutputData
{
    /** @param list<array{productId: int<1, max>, productVariantId: int<1, max>, title: string, makerName: string, taxonomyName: string, quantity: int<1, max>, price: int<1, max>, taxRate: int<1, 100>, point: int<0, max>|null}> $items */
    public function __construct(
        public readonly int $storeId,
        public readonly array $items,
    ) {
    }
}
