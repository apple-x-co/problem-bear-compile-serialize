/* staff_member_login_attempt_delete_by_email */
DELETE
FROM `staff_member_login_attempts`
WHERE `email` = :email;
