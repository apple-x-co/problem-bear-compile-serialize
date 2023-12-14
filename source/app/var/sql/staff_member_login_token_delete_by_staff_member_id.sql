/* staff_member_login_token_delete_by_staff_member_id */
DELETE
FROM `staff_member_login_tokens`
WHERE `staff_member_id` = :staffMemberId;
