<?php

declare(strict_types=1);

namespace AppCore\Domain\Fincode;

enum FincodeStatus: string
{
    case UNPROCESSED = 'UNPROCESSED';
    case AWAITING_CUSTOMER_PAYMENT = 'AWAITING_CUSTOMER_PAYMENT';
    case AUTHORIZED = 'AUTHORIZED';
    case CAPTURED = 'CAPTURED';
    case CANCELED = 'CANCELED';
    case EXPIRED = 'EXPIRED';
}
