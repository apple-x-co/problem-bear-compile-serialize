/* popular_product_update */
UPDATE `popular_products`
SET `position`    = :position,
    `update_date` = NOW()
WHERE `id` = :id;
