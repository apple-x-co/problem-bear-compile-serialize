/* admin_login_token_add */
INSERT INTO `admin_login_tokens` (`id`, `admin_id`, `token`, `expire_date`, `created_date`)
VALUES (0, :adminId, :token, :expireDate, NOW());

SELECT LAST_INSERT_ID() AS id;
