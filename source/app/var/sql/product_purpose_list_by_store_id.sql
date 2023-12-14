/* product_purpose_list_by_store_id */
SELECT `id`, `product_id`, `purpose_id`, `position`
FROM `product_purposes`
WHERE EXISTS (SELECT 1
              FROM `products`
              WHERE `products`.`id` = `product_purposes`.`product_id`
                AND `products`.`store_id` = :storeId)
ORDER BY `product_id`, `position`;
