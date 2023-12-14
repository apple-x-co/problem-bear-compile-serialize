<?php

declare(strict_types=1);

namespace AppCore\Domain\AdminSession;

enum AdminPasswordLocking: string
{
    case Locked = 'Locked';
    case Unlocked = 'Unlocked';
}
