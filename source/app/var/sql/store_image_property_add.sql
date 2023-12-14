/* store_image_property_add */
INSERT INTO `store_image_properties` (`id`, `store_image_id`, `name`, `value`, `create_date`, `update_date`)
VALUES (0, :storeImageId, :name, :value, NOW(), NOW())

SELECT LAST_INSERT_ID() AS id;
