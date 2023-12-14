/* shop_regular_holiday_add */
INSERT INTO `shop_regular_holidays` (`id`, `shop_id`, `day_of_week`, `create_date`, `update_date`)
VALUES (0, :shopId, :dayOfWeek, NOW(), NOW())

SELECT LAST_INSERT_ID() AS id;
