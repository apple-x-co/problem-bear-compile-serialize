/* product_maker_list_by_store_id */
SELECT `id`, `product_id`, `maker_id`, `position`
FROM `product_makers`
WHERE EXISTS (SELECT 1
              FROM `products`
              WHERE `products`.`id` = `product_makers`.`product_id`
                AND `products`.`store_id` = :storeId)
ORDER BY `product_id`, `position`;
