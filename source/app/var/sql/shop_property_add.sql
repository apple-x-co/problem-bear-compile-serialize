/* shop_property_add */
INSERT INTO `shop_properties` (`id`, `shop_id`, `name`, `value`, `create_date`, `update_date`)
VALUES (0, :shopId, :name, :value, NOW(), NOW())

SELECT LAST_INSERT_ID() AS id;
