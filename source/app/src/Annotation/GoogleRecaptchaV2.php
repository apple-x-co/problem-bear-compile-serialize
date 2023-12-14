<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class GoogleRecaptchaV2
{
    public function __construct(
        public readonly string $onFailure = 'onPostGoogleRecaptchaV2Failed',
    ) {
    }
}
