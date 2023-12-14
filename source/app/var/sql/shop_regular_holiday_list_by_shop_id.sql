/* shop_regular_holiday_list_by_shop_id */
SELECT `id`, `shop_id`, `day_of_week`
FROM `shop_regular_holidays`
WHERE `shop_id` = :shopId
ORDER BY `day_of_week`;
