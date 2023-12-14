/* taxonomy_update */
UPDATE `taxonomies`
SET `parent_id` = :parentId,
    `name`      = :name,
    `position`  = :position
WHERE `id` = :id;
