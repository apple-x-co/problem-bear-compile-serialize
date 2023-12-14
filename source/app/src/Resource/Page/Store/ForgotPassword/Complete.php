<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Store\ForgotPassword;

use MyVendor\MyProject\Resource\Page\BaseStorePage;

class Complete extends BaseStorePage
{
    public function onGet(): static
    {
        return $this;
    }
}
