/* customer_reward_item_by_unique_key */
SELECT `id`, `customer_id`, `store_id`, `remaining_point`
FROM `customer_rewards`
WHERE `customer_id` = :customerId
  AND `store_id` = :storeId;
