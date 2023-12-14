<?php

declare(strict_types=1);

namespace AppCore\Domain\PaymentIntent;

use AppCore\Domain\Uuid;

final class IdempotencyToken
{
    public function __invoke(): string
    {
        return (new Uuid())();
    }
}