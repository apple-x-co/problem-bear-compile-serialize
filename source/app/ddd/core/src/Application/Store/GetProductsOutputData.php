<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

final class GetProductsOutputData
{
    /** @param list<array{id: int<1, max>, title: string}> $products */
    public function __construct(public readonly array $products)
    {
    }
}
