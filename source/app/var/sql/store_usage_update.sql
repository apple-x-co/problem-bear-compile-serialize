/* store_usage_update */
UPDATE `store_usages`
SET `status`      = :status,
    `update_date` = :updateDate
WHERE `id` = :id;
