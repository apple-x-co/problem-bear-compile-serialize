<?php

declare(strict_types=1);

namespace AppCore\Domain\PaymentIntent;

enum PaymentGateway: string
{
    case STORE = 'store'; // use Point

    case SHOP = 'shop'; // use Cash
    case FINCODE = 'fincode'; // use CreditCard or PayPay
}
