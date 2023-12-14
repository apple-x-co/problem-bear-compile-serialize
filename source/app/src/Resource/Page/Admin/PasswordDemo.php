<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin;

use MyVendor\MyProject\Annotation\AdminGuard;
use MyVendor\MyProject\Annotation\AdminPasswordProtect;
use MyVendor\MyProject\Resource\Page\BaseAdminPage;

class PasswordDemo extends BaseAdminPage
{
    #[AdminGuard]
    #[AdminPasswordProtect]
    public function onGet(): static
    {
        return $this;
    }
}
