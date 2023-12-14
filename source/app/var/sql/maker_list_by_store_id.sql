/* maker_list_by_store_id */
SELECT `id`, `store_id`, `parent_id`, `name`, `position`
FROM `maker`
WHERE `store_id` = :storeId;
