/* store_property_add */
INSERT INTO `store_properties` (`id`, `store_id`, `name`, `value`, `create_date`, `update_date`)
VALUES (0, :storeId, :name, :value, NOW(), NOW())

SELECT LAST_INSERT_ID() AS id;
