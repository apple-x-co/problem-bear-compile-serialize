<?php

declare(strict_types=1);

namespace AppCore\Domain\StoreFeePaymentIntent;

enum StoreFeePaymentGateway: string
{
    case GMO_PG = 'gmo_pg';
}
