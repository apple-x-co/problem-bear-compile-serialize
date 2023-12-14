<?php

declare(strict_types=1);

namespace AppCore\Domain\SalonSession;

enum SalonPasswordLocking: string
{
    case Locked = 'Locked';
    case Unlocked = 'Unlocked';
}
