/* shop_holiday_list_by_company_id */
SELECT `id`, `shop_id`, `date`
FROM `shop_holidays`
WHERE EXISTS (SELECT 1
              FROM `shops`
              WHERE `shops`.`id` = `shop_holidays`.`shop_id`
                AND `shops`.`company_id` = :companyId)
ORDER BY `shop_id`, `date`;
