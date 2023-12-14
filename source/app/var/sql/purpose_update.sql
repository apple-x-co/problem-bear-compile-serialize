/* purpose_update */
UPDATE `purposes`
SET `parent_id` = :parentId,
    `name`      = :name,
    `position`  = :position
WHERE `id` = :id;
