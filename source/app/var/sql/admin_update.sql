/* admin_update */
UPDATE `admins`
SET `name` = :name,
    `username` = :username,
    `password` = :password,
    `update_date` = NOW()
WHERE `id` = :id;
