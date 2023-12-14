<?php

declare(strict_types=1);

namespace AppCore\Domain\StaffMember;

use AppCore\Exception\RuntimeException;

final class ForbiddenTransitionException extends RuntimeException
{
}
