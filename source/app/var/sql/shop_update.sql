/* shop_update */
UPDATE `shops`
SET `area_id`     = :areaId,
    `name`        = :name,
    `position`    = :position,
    `update_date` = NOW()
WHERE `id` = :id;

