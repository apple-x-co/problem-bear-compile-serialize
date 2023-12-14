<?php

declare(strict_types=1);

namespace AppCore\Domain\CustomerReward;

enum PointType: string
{
    case EARNING = 'earning';
    case SPENDING = 'spending';
}
