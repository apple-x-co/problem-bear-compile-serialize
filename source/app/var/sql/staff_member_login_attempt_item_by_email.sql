/* staff_member_login_attempt_item_by_email */
SELECT `id`, `email`, `number_of_trials`, `last_exception`, `last_trial_date`, `prev_trial_date`, `expire_date`
FROM `staff_member_login_attempts`
WHERE `email` = :email;
