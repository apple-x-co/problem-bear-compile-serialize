<?php

declare(strict_types=1);

namespace AppCore\Domain\PaymentMethod;

enum PaymentMethodKey: string
{
    case CREDIT_CARD = 'credit_card';
    case PAY_PAY = 'pay_pay';
    case CASH = 'cash';
    case POINT = 'point';
    case BANK = 'bank';
}
