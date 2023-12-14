/* shop_property_list_by_company_id */
SELECT `id`, `shop_id`, `name`, `value`
FROM `shop_properties`
WHERE EXISTS (SELECT 1
              FROM `shops`
              WHERE `shops`.`id` = `shop_properties`.`shop_id`
                AND `shops`.`company_id` = :companyId)
ORDER BY `shop_id`, `name`, `value`;
