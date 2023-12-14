<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface StaffMemberPermissionCommandInterface
{
    /**
     * @param int<1, max> $staffMemberId
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('staff_member_permission_add', type: 'row')]
    public function add(
        int $staffMemberId,
        string $access,
        string $resourceName,
        string $permission,
    ): array;

    /** @param int<1, max> $id */
    #[DbQuery('staff_member_permission_update', type: 'row')]
    public function update(
        int $id,
        string $access,
        string $resourceName,
        string $permission,
    ): void;

    /**
     * @param int<1, max>       $staffMemberId
     * @param list<int<1, max>> $aliveIds
     */
    #[DbQuery('staff_member_permission_delete_old', type: 'row')]
    public function deleteOld(int $staffMemberId, array $aliveIds): void;
}
