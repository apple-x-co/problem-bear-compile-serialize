/* shop_holiday_list_by_shop_id */
SELECT `id`, `shop_id`, `date`
FROM `shop_holidays`
WHERE `shop_id` = :shopId
ORDER BY `date`;
