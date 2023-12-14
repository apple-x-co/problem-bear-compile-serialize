<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type StaffMemberLoginAttemptItem = array{
 *     id: string,
 *     email: string,
 *     number_of_trials: string,
 *     last_trial_date: string,
 *     prev_trial_date: string|null,
 *     expire_date: string,
 * }
 */
interface StaffMemberLoginAttemptQueryInterface
{
    /** @psalm-return StaffMemberLoginAttemptItem|null */
    #[DbQuery('staff_member_login_attempt_item_by_email', type: 'row')]
    public function itemByEmail(string $email): array|null;
}
