<?php

declare(strict_types=1);

namespace AppCore\Domain\Fincode;

enum FincodePayType: string
{
    case Card = 'Card';
    case Applepay = 'Applepay';
    case Konbini = 'Konbini';
    case Paypay = 'Paypay';
}
