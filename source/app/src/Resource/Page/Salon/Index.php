<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Salon;

use MyVendor\MyProject\Annotation\SalonGuard;
use MyVendor\MyProject\Resource\Page\BaseSalonPage;

class Index extends BaseSalonPage
{
    public function __construct()
    {
    }

    #[SalonGuard]
    public function onGet(): static
    {
        return $this;
    }
}
