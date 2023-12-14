/* product_taxonomy_list_by_store_id */
SELECT `id`, `product_id`, `taxonomy_id`, `position`
FROM `product_taxonomies`
WHERE EXISTS (SELECT 1
              FROM `products`
              WHERE `products`.`id` = `product_taxonomies`.`product_id`
                AND `products`.`store_id` = :storeId)
ORDER BY `product_id`, `position`;
