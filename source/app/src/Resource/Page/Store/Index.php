<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Store;

use MyVendor\MyProject\Annotation\CustomerGuard;
use MyVendor\MyProject\Resource\Page\BaseStorePage;

class Index extends BaseStorePage
{
    #[CustomerGuard]
    public function onGet(): static
    {
        return $this;
    }
}
