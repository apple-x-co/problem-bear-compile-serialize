/* product_maker_update */
UPDATE `product_makers`
SET `position`    = :position,
    `update_date` = NOW()
WHERE `id` = :id;
