<?php

declare(strict_types=1);

namespace AppCore\Domain\Stock;

use AppCore\Exception\RuntimeException;

final class IdempotencyException extends RuntimeException
{
}
