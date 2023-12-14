<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

final class VerifyJoinCustomerInputData
{
    public function __construct(public readonly string $signature)
    {
    }
}
