<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface StaffMemberLoginTokenCommandInterface
{
    /**
     * @param int<1, max> $staffMemberId
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('staff_member_login_token_add', type: 'row')]
    public function add(
        int $staffMemberId,
        string $token,
        DateTimeImmutable $expireDate,
    ): array;

    /** @param int<1, max> $staffMemberId */
    #[DbQuery('staff_member_login_token_delete_by_staff_member_id', type: 'row')]
    public function deleteByStaffMemberId(int $staffMemberId): void;
}
