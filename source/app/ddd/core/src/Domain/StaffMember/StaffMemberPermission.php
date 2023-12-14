<?php

declare(strict_types=1);

namespace AppCore\Domain\StaffMember;

use AppCore\Domain\AccessControl\Access;
use AppCore\Domain\AccessControl\Permission;

final class StaffMemberPermission
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /** @param int<0, max> $id */
    public function __construct(
        public readonly Access $access,
        public readonly string $resourceName,
        public readonly Permission $permission,
        public readonly int $id = self::ID0,
    ) {
    }

    /** @param int<1, max> $id */
    public static function reconstruct(
        int $id,
        Access $access,
        string $resourceName,
        Permission $permission,
    ): StaffMemberPermission {
        return new self(
            $access,
            $resourceName,
            $permission,
            $id,
        );
    }

    /** @return int<1, max>|null */
    public function getNewId(): int|null
    {
        return $this->newId;
    }

    /** @param int<1, max> $newId */
    public function setNewId(int $newId): void
    {
        $this->newId = $newId;
    }
}
