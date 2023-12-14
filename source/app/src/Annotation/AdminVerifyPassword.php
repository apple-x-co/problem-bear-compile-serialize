<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class AdminVerifyPassword
{
    public function __construct(
        public readonly string $onFailure = 'onPostAuthenticationFailed',
    ) {
    }
}
