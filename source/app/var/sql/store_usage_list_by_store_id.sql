/* store_usage_list_by_store_id */
SELECT `id`, `store_id`, `description`, `target_date`, `total_price`, `status`
FROM `store_usages`
WHERE `store_id` = :storeId;
