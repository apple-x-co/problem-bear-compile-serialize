/* featured_product_update */
UPDATE `featured_products`
SET `position`    = :position,
    `update_date` = NOW()
WHERE `id` = :id;
