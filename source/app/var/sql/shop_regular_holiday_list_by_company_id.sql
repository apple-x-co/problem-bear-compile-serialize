/* shop_regular_holiday_list_by_company_id */
SELECT `id`, `shop_id`, `day_of_week`
FROM `shop_regular_holidays`
WHERE EXISTS (SELECT 1
              FROM `shops`
              WHERE `shops`.`id` = `shop_regular_holidays`.`shop_id`
                AND `shops`.`company_id` = :companyId)
ORDER BY `shop_id`, `day_of_week`
