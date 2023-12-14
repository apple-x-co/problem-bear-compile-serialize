/* product_variant_list_by_product_id */
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
WHERE `product_id` = :productId
ORDER BY `code`;
