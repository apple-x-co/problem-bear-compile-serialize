<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin;

use MyVendor\MyProject\Annotation\AdminGuard;
use MyVendor\MyProject\Resource\Page\BaseAdminPage;

class Index extends BaseAdminPage
{
    public function __construct()
    {
    }

    #[AdminGuard]
    public function onGet(): static
    {
        return $this;
    }
}
