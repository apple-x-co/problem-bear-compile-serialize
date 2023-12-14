<?php

declare(strict_types=1);

namespace AppCore\Domain\AccessControl;

use function array_reduce;

class AccessControl implements AccessControlInterface
{
    /** @var array<string, bool> */
    private array $resources = [];

    /** @var array<string, list<Permission>> */
    private array $allowRules = [];

    /** @var array<string, list<Permission>> */
    private array $denyRules = [];

    public function addResource(string $resourceName): self
    {
        $clone = clone $this;
        $clone->resources[$resourceName] = true;

        return $clone;
    }

    public function allow(string $resourceName, Permission $permission): self
    {
        $clone = clone $this;
        $clone->setRule($resourceName, Access::Allow, $permission);

        return $clone;
    }

    public function deny(string $resourceName, Permission $permission): self
    {
        $clone = clone $this;
        $clone->setRule($resourceName, Access::Deny, $permission);

        return $clone;
    }

    /** @SuppressWarnings(PHPMD.UnusedPrivateMethod) */
    private function setRule(string $resourceName, Access $access, Permission $permission): void
    {
        if (! isset($this->resources[$resourceName])) {
            return;
        }

        if ($access === Access::Allow) {
            $this->allowRules[$resourceName][] = $permission;

            return;
        }

        $this->denyRules[$resourceName][] = $permission;
    }

    public function isAllowed(string $resourceName, Permission $permission): bool
    {
        if (! empty($this->denyRules[$resourceName])) {
            return array_reduce(
                $this->denyRules[$resourceName],
                static function (bool $carry, Permission $item) use ($permission) {
                    if ($item === $permission) {
                        $carry = false;
                    }

                    return $carry;
                },
                true,
            );
        }

        return array_reduce(
            $this->allowRules[$resourceName] ?? [],
            static function (bool $carry, Permission $item) use ($permission) {
                if ($item === $permission || $item === Permission::Privilege) {
                    $carry = true;
                }

                return $carry;
            },
            false,
        );
    }
}
