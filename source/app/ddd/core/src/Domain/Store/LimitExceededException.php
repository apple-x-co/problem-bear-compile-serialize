<?php

declare(strict_types=1);

namespace AppCore\Domain\Store;

use AppCore\Exception\RuntimeException;

class LimitExceededException extends RuntimeException
{
}
