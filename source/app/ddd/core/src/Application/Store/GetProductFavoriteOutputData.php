<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

final class GetProductFavoriteOutputData
{
    /** @param list<int<1, max>> $productIds */
    public function __construct(public readonly array $productIds)
    {
    }
}
