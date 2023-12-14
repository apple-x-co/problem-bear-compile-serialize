/* product_property_item */
SELECT `id`, `product_id`, `name`, `value`
FROM `product_properties`
WHERE `id` = :id;
