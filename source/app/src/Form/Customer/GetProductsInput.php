<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Customer;

final class GetProductsInput
{
    /** @param list<array{id: string}> $products */
    public function __construct(public array $products)
    {
    }
}
