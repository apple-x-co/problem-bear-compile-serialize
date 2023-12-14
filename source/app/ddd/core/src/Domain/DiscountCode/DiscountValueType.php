<?php

declare(strict_types=1);

namespace AppCore\Domain\DiscountCode;

enum DiscountValueType: string
{
    case PERCENTAGE = 'percentage';
    case FIXED_AMOUNT = 'fixed_amount';
}
