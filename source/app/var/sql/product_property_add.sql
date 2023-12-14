/* product_property_add */
INSERT INTO `product_properties` (`id`, `product_id`, `name`, `value`, `create_date`, `update_date`)
VALUES (0, :productId, :name, :value, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
