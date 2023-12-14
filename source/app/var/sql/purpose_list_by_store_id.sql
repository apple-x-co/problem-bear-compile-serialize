/* purpose_list_by_store_id */
SELECT `id`, `store_id`, `parent_id`, `name`, `position`
FROM `purposes`
WHERE `store_id` = :storeId;
