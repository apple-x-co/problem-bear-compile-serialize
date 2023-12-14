/* shop_item */
SELECT `id`, `company_id`, `area_id`, `name`, `position`
FROM `shops`
WHERE `company_id` = :companyId;
ORDER BY `position`;
