/* taxonomy_list_by_store_id */
SELECT `id`, `store_id`, `parent_id`, `name`, `position`
FROM `taxonomies`
WHERE `store_id` = :storeId;
