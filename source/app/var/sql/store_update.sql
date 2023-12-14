/* store_update */
UPDATE `stores`
SET `url`         = :url,
    `key`         = :key,
    `name`        = :name,
    `status`      = :status,
    `leave_date`  = :leaveDate,
    `void_date`   = :voidDate,
    `update_date` = NOW()
WHERE `id` = :id;
