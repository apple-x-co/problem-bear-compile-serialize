/* customer_point_add */
INSERT INTO `customer_points` (`id`, `customer_reward_id`, `uuid`, `type`, `transaction_date`, `expire_date`, `point`,
                               `remaining_point`, `invalidation_date`, `invalidation_reason`, `create_date`,
                               `update_date`)
VALUES (0, :customerRewardId, :uuid, :type, :transactionDate, :expireDate, :point, :remainingPoint, :invalidationDate,
        :invalidationReason, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
