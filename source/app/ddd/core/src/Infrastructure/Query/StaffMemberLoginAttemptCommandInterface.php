<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface StaffMemberLoginAttemptCommandInterface
{
    /** @return array{id: int} */
    #[DbQuery('staff_member_login_attempt_add', type: 'row')]
    public function add(
        string $email,
        string $lastException,
        DateTimeImmutable $lastTrialDate,
        DateTimeImmutable $expireDate,
    ): array;

    /** @param int<1, max> $id */
    #[DbQuery('staff_member_login_attempt_update')]
    public function update(
        int $id,
        string $lastException,
        DateTimeImmutable $lastTrialDate,
        DateTimeImmutable $expireDate,
    ): void;

    #[DbQuery('staff_member_login_attempt_delete_by_email')]
    public function deleteByEmail(string $email): void;
}
