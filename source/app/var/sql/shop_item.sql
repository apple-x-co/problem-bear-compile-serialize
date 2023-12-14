/* shop_item */
SELECT `id`, `company_id`, `area_id`, `name`, `position`
FROM `shops`
WHERE `id` = :id;
