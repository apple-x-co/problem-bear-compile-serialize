/* store_image_delete_old */
INSERT INTO `trash_media_files` (`id`, `table_name`, `identifier`, `original_file_name`, `url`, `path`, `file_name`,
                                 `create_date`)
SELECT 0,
       'store_images',
       `id`,
       `original_file_name`,
       `url`,
       `path`,
       `file_name`,
       NOW()
FROM `store_images`
WHERE `store_id` = :storeId
  AND `group` = :group
  AND `id` NOT IN (:aliveIds);

DELETE
FROM `store_image_properties`
WHERE EXISTS (SELECT 1
              FROM `store_images`
              WHERE `store_images`.`id` = `store_image_properties`.`store_image_id`
                AND `store_images`.`store_id` = :storeId
                AND `store_images`.`group` = :group
                AND `store_images`.`id` NOT IN (:aliveIds);

DELETE
FROM `store_images`
WHERE `store_id` = :storeId
  AND `group` = :group
  AND `id` NOT IN (:aliveIds);
