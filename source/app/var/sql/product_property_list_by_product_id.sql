/* product_property_list_by_product_id */
SELECT `id`, `product_id`, `name`, `value`
FROM `product_properties`
WHERE `product_id` = :productId
ORDER BY `name`, `value`;
