/* admin_login_token_delete_by_admin_id */
DELETE
FROM `admin_login_tokens`
WHERE `admin_id` = :adminId;
