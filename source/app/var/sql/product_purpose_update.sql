/* product_purpose_update */
UPDATE `product_purposes`
SET `position`    = :position,
    `update_date` = NOW()
WHERE `id` = :id;
