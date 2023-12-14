<?php

declare(strict_types=1);

namespace AppCore\Domain\AccessControl;

enum Access: string
{
    case Allow = 'allow';
    case Deny = 'deny';
}
