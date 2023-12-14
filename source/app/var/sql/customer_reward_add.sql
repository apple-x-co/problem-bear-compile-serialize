/* customer_reward_add */
INSERT INTO `customer_rewards` (`id`, `customer_id`, `store_id`, `remaining_point`, `create_date`, `create_date`)
VALUES (0, :customerId, :storeId, :remainingPoint, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
