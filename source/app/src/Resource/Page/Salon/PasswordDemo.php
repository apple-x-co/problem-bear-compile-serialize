<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Salon;

use MyVendor\MyProject\Annotation\SalonGuard;
use MyVendor\MyProject\Annotation\SalonPasswordProtect;
use MyVendor\MyProject\Resource\Page\BaseSalonPage;

class PasswordDemo extends BaseSalonPage
{
    #[SalonGuard]
    #[SalonPasswordProtect]
    public function onGet(): static
    {
        return $this;
    }
}
