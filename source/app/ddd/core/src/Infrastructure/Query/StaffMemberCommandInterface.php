<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface StaffMemberCommandInterface
{
    /**
     * @param int<1, max>      $companyId
     * @param int<1, max>|null $shopId
     * @param int<1, max>      $position
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('staff_member_add', type: 'row')]
    public function add(
        int $companyId,
        int|null $shopId,
        string $name,
        string $code,
        string|null $email,
        string $password,
        string $message,
        int $position,
        string $status,
    ): array;

    /**
     * @param int<1, max>      $id
     * @param int<1, max>|null $shopId
     * @param int<1, max>      $position
     */
    #[DbQuery('staff_member_update', type: 'row')]
    public function update(
        int $id,
        int|null $shopId,
        string $name,
        string $code,
        string|null $email,
        string $password,
        string $message,
        int $position,
        string $status,
    ): void;

    /** @param int<1, max> $id */
    #[DbQuery('staff_member_delete', type: 'row')]
    public function delete(int $id): void;
}
