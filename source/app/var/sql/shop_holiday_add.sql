/* shop_holiday_add */
INSERT INTO `shop_holidays` (`id`, `shop_id`, `date`, `create_date`, `update_date`)
VALUES (0, :shopId, :date, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
