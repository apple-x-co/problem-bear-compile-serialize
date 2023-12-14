/* tax_update */
UPDATE `taxes`
SET `name`        = :name,
    `rate`        = :rate,
    `update_date` = NOW()
WHERE `id` = :id;
