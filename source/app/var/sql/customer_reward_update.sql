/* customer_reward_update */
UPDATE `customer_rewards`
SET `remaining_point` = :remaining_point,
    `update_date`     = NOW()
WHERE `id` = :id
