<?php

declare(strict_types=1);

namespace AppCore\Domain\AccessControl;

enum Permission: string
{
    case Privilege = 'privilege';
    case Read = 'read';
    case Write = 'write';
}
