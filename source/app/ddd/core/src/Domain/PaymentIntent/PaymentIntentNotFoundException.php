<?php

declare(strict_types=1);

namespace AppCore\Domain\PaymentIntent;

use AppCore\Exception\RuntimeException;

final class PaymentIntentNotFoundException extends RuntimeException
{
}
