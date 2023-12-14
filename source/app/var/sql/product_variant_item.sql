/* product_variant_item */
SELECT `id`,
       `product_id`,
       `title`,
       `code`,
       `sku`,
       `original_price`,
       `price`,
       `discount_price`,
       `pickup_duration_days`
FROM `product_variants`
WHERE `id` = :id;
