/* product_taxonomy__update */
UPDATE `product_taxonomies`
SET `position`    = :position,
    `update_date` = NOW()
WHERE `id` = :id;
