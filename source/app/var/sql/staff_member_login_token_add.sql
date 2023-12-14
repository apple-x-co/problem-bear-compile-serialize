/* staff_member_login_token_add */
INSERT INTO `staff_member_login_tokens` (`id`, `staff_member_id`, `token`, `expire_date`, `created_date`)
VALUES (0, :staffMemberId, :token, :expireDate, NOW());

SELECT LAST_INSERT_ID() AS id;
