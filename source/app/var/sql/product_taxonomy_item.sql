/* product_taxonomy_item */
SELECT `id`, `product_id`, `taxonomy_id`, `position`
FROM `product_taxonomies`
WHERE `id` = :id;
