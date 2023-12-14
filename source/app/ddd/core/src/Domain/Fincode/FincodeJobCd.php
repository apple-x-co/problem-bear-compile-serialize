<?php

declare(strict_types=1);

namespace AppCore\Domain\Fincode;

enum FincodeJobCd: string
{
    case AUTH = 'AUTH';
    case CAPTURE = 'CAPTURE';
}
