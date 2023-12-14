/* area_update */
UPDATE `areas`
SET `name` = :name,
    `position` = :position,
    `update_date` = NOW()
WHERE `id` = :id;
