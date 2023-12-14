<?php

declare(strict_types=1);

namespace AppCore\Domain\AccessControl;

interface AccessControlInterface
{
    public function addResource(string $resourceName): self;

    public function allow(string $resourceName, Permission $permission): self;

    public function deny(string $resourceName, Permission $permission): self;

    public function isAllowed(string $resourceName, Permission $permission): bool;
}
