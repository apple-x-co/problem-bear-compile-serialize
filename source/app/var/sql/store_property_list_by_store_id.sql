/* store_property_list_by_store_id */
SELECT `id`, `store_id`, `name`, `value`
FROM `store_properties`
WHERE `store_id` = :storeId
ORDER BY `name`;
