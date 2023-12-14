<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Customer;

final class AddViewProductInput
{
    /** @param int<1, max> $productId */
    public function __construct(public int $productId)
    {
    }
}
