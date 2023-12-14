<?php

declare(strict_types=1);

namespace AppCore\Domain\PaymentMethod;

enum PaymentMethodRole: string
{
    case SELLER = 'seller';
    case CONSUMER = 'consumer';
}
