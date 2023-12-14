<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

/**
 * @psalm-type StaffMemberTokenItem = array{
 *     id: string,
 *     staff_member_id: string,
 *     token: string,
 *     expire_date: string,
 * }
 */
interface StaffMemberLoginTokenQueryInterface
{
    /** @psalm-return StaffMemberTokenItem|null */
    public function itemByToken(string $token): array|null;
}
