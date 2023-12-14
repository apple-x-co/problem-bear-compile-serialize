<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Store;

use BEAR\Resource\JsonRenderer;
use MyVendor\MyProject\Annotation\MeGuard;
use MyVendor\MyProject\Auth\CustomerAuthenticatorInterface;
use MyVendor\MyProject\Resource\Page\BaseStoreMeApi;

/** @SuppressWarnings(PHPMD.ShortClassName) */
class Me extends BaseStoreMeApi
{
    public function __construct(
        private readonly CustomerAuthenticatorInterface $authenticator,
    ) {
    }

    #[MeGuard]
    public function onGet(): static
    {
        $identity = $this->authenticator->getIdentity();

        $this->renderer = new JsonRenderer();
        $this->body['me'] = ['name' => $identity->displayName];

        return $this;
    }
}
