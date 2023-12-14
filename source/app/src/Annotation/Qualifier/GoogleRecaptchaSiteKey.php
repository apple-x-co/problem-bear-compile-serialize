<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Annotation\Qualifier;

use Attribute;
use Ray\Di\Di\Qualifier;

#[Attribute(Attribute::TARGET_PARAMETER)]
#[Qualifier]
final class GoogleRecaptchaSiteKey
{
}
