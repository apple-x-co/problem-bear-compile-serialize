<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

final class GetCartInputData
{
    public function __construct(public readonly int $customerId)
    {
    }
}
