<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Annotation;

use AppCore\Domain\AccessControl\Permission;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class RequiredPermission
{
    public function __construct(
        public readonly string $resourceName,
        public readonly Permission $permission,
    ) {
    }
}
