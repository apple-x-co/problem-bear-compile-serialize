/* store_item_by_key */
SELECT `id`, `url`, `key`, `name`, `status`, `leave_date`, `void_date`
FROM `stores`
WHERE `key` = :key;
