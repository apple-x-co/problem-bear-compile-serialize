/* product_variant_list_by_store_id */
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
WHERE EXISTS (SELECT 1
              FROM `products`
              WHERE `products`.`id` = `product_variants`.`product_id`
                AND `products`.`store_id` = :storeId)
ORDER BY `product_id`, `code`;
