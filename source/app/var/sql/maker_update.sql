/* maker_update */
UPDATE `makers`
SET `parent_id`   = :parentId,
    `name`        = :name,
    `position`    = :position,
    `update_date` = NOW()
WHERE `id` = :id;
