/* staff_member_login_attempt_update */
UPDATE `staff_member_login_attempts`
SET `number_of_trials` = `number_of_trials` + 1,
    `last_exception`   = :lastException,
    `last_trial_date`  = :lastTrialDate,
    `prev_trial_date`  = `last_trial_date`,
    `expire_date`      = :expireDate,
    `update_date`      = NOW()
WHERE `id` = :id;
