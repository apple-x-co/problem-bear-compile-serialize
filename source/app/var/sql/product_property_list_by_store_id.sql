/* product_property_list_by_store_id */
SELECT `id`, `product_id`, `name`, `value`
FROM `product_properties`
WHERE EXISTS (SELECT 1
              FROM `products`
              WHERE `products`.`id` = `product_properties`.`product_id`
                AND `products`.`store_id` = :storeId)
ORDER BY `product_id`, `name`, `value`;
