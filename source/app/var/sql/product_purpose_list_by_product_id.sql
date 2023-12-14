/* product_purpose_list_by_product_id */
SELECT `id`, `product_id`, `purpose_id`, `position`
FROM `product_purposes`
WHERE `product_id` = :productId
ORDER BY `position`;
