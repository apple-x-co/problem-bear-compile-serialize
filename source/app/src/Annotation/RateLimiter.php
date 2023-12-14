<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class RateLimiter
{
    /** @see https://www.php.net/datetime.formats.relative */
    public function __construct(
        public readonly int $limit = 10,
        public readonly string $interval = '30 minutes',
    ) {
    }
}
