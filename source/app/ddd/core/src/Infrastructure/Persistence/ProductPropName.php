<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

enum ProductPropName: string
{
    case VideoUrl = 'VideoUrl';
    case Description = 'Description';
    case Volume = 'Volume';
    case CountryOfOrigin = 'CountryOfOrigin';
    case Specification = 'Specification';
}
