/* shop_holiday_delete_old */
DELETE
FROM `shop_holidays`
WHERE `shop_id` = :shopId
  AND `id` NOT IN (:aliveIds);
