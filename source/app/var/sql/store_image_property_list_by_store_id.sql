/* store_image_property_list_by_store_id */
SELECT `id`, `store_image_id`, `name`, `value`
FROM `store_image_properties`
WHERE EXISTS (SELECT 1
              FROM `store_images`
              WHERE `store_images`.`id` = `store_image_properties`.`store_image_id`
                AND `store_images`.`store_id` = :storeId)
ORDER BY `store_image_id`, `name`;
