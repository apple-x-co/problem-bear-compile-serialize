/* shop_property_list_by_shop_id */
SELECT `id`, `shop_id`, `name`, `value`
FROM `shop_properties`
WHERE `shop_id` = :shopId
ORDER BY `name`, `value`;
