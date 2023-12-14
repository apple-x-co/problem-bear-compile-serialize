/* customer_point_update */
UPDATE `customer_points`
SET `remaining_point`     = :remainingPoint,
    `invalidation_date`   = :invalidationDate,
    `invalidation_reason` = :invalidationReason,
    `update_date`         = NOW()
WHERE `id` = :id;
