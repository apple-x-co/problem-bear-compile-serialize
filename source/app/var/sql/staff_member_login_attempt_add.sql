/* staff_member_login_attempt_add */
INSERT INTO `staff_member_login_attempts` (`id`, `email`, `number_of_trials`, `last_exception`, `last_trial_date`,
                                           `prev_trial_date`, `expire_date`, `created_date`, `update_date`)
VALUES (0, :email, 1, :lastException, :lastTrialDate, null, :expireDate, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
