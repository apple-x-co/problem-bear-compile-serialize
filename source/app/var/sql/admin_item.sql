/* admin_item */
SELECT `id`, `name`, `username`, `password`
FROM `admins`
WHERE `id` = :id;
