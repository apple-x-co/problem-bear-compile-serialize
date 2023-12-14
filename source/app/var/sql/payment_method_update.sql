/* payment_method_update */
UPDATE `payment_methods`
SET `name`        = :name,
    `fee`         = :fee,
    `available`   = :available,
    `position`    = :position,
    `update_date` = NOW()
WHERE `id` = :id;
