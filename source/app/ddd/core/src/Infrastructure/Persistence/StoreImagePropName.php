<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

enum StoreImagePropName: string
{
    case Title = 'Title';
    case Subtitle = 'Subtitle';
    case LinkUrl = 'LinkUrl';
}
