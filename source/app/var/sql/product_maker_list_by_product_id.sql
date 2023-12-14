/* product_maker_list_by_product_id */
SELECT `id`, `product_id`, `maker_id`, `position`
FROM `product_makers`
WHERE `product_id` = :productId
ORDER BY `position`;
