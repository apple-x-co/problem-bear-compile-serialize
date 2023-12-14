/* store_usage_item */
SELECT `id`, `store_id`, `description`, `target_date`, `total_price`, `status`
FROM `store_usages`
WHERE `id` = :id;
