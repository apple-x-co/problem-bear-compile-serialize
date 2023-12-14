<?php

declare(strict_types=1);

namespace AppCore\Domain\DiscountCode;

use AppCore\Exception\RuntimeException;

final class ForbiddenTransitionException extends RuntimeException
{
}
