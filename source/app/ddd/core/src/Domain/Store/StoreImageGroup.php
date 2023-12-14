<?php

declare(strict_types=1);

namespace AppCore\Domain\Store;

enum StoreImageGroup: string
{
    case LOGO = 'logo';
    case HERO = 'hero';
}
