<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

enum ShopPropName: string
{
    case PostalCode = 'PostalCode';
    case State = 'State';
    case City = 'City';
    case AddressLine1 = 'AddressLine1';
    case AddressLine2 = 'AddressLine2';
    case PhoneNumber = 'PhoneNumber';
    case Latitude = 'Latitude';
    case Longitude = 'Longitude';
}
