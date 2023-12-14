<?php

declare(strict_types=1);

namespace AppCore\Domain\Order;

use AppCore\Exception\RuntimeException;

final class OrderNotFoundException extends RuntimeException
{
}
