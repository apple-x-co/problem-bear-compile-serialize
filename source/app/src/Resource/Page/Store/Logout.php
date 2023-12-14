<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Store;

use BEAR\Resource\NullRenderer;
use MyVendor\MyProject\Annotation\CustomerGuard;
use MyVendor\MyProject\Annotation\CustomerLogout;
use MyVendor\MyProject\Resource\Page\BaseStorePage;

class Logout extends BaseStorePage
{
    #[CustomerGuard]
    #[CustomerLogout]
    public function onPost(): static
    {
        $this->renderer = new NullRenderer();

        return $this;
    }
}
