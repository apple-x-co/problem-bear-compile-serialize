/* store_item */
SELECT `id`, `url`, `key`, `name`, `status`, `leave_date`, `void_date`
FROM `stores`
WHERE `id` = :id;
