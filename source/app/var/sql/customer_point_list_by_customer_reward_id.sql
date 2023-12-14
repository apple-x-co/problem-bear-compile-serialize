/* customer_point_list_by_customer_reward_id */
SELECT `id`,
       `customer_reward_id`,
       `uuid`,
       `type`,
       `transaction_date`,
       `expire_date`,
       `point`,
       `remaining_point`,
       `invalidation_date`,
       `invalidation_reason`
FROM `customer_points`
WHERE `customer_reward_id` = :customerRewardId;
