/* store_image_list_by_store_id */
SELECT `id`,
       `store_id`,
       `group`,
       `size`,
       `media_type`,
       `width`,
       `height`,
       `original_file_name`,
       `url`,
       `path`,
       `file_name`,
       `md5`,
       `position`,
       `create_date`,
       `update_date`
FROM `store_images`
WHERE `store_id` = :storeId
ORDER BY `group`, `position`;
