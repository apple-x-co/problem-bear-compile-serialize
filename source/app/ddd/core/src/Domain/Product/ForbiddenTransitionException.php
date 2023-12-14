<?php

declare(strict_types=1);

namespace AppCore\Domain\Product;

use AppCore\Exception\RuntimeException;

final class ForbiddenTransitionException extends RuntimeException
{
}
