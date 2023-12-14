<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Salon;

use BEAR\Resource\NullRenderer;
use MyVendor\MyProject\Annotation\SalonGuard;
use MyVendor\MyProject\Annotation\SalonLogout;
use MyVendor\MyProject\Resource\Page\BaseSalonPage;

class Logout extends BaseSalonPage
{
    #[SalonGuard]
    #[SalonLogout]
    public function onPost(): static
    {
        $this->renderer = new NullRenderer();

        return $this;
    }
}
