<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Store\SignUp;

use MyVendor\MyProject\Resource\Page\BaseStorePage;

class Verified extends BaseStorePage
{
    public function onGet(): static
    {
        return $this;
    }
}
