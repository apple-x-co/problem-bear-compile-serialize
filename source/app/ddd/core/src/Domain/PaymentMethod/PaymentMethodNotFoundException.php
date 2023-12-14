<?php

declare(strict_types=1);

namespace AppCore\Domain\PaymentMethod;

use AppCore\Exception\RuntimeException;

final class PaymentMethodNotFoundException extends RuntimeException
{
}
