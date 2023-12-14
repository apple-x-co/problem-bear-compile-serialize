<?php

declare(strict_types=1);

namespace AppCore\Domain\DiscountCode;

enum DiscountTargetSelection: string
{
    case ALL = 'all';
    case ENTITLED = 'entitled';
}
