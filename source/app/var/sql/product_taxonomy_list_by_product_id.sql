/* product_taxonomy_list_by_product_id */
SELECT `id`, `product_id`, `taxonomy_id`, `position`
FROM `product_taxonomies`
WHERE `product_id` = :productId
ORDER BY `position`;
