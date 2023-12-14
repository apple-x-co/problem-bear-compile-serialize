/* shop_image_delete_old */
INSERT INTO `trash_media_files` (`id`, `table_name`, `identifier`, `original_file_name`, `url`, `path`, `file_name`,
                                 `create_date`)
SELECT 0,
       'shop_images',
       `id`,
       `original_file_name`,
       `url`,
       `path`,
       `file_name`,
       NOW()
FROM `shop_images`
WHERE `shop_id` = :shopId
  AND `group` = :group,
  AND `id` NOT IN (:aliveIds);

DELETE
FROM `shop_images`
WHERE `shop_id` = :shopId
  AND `group` = :group,
  AND `id` NOT IN (:aliveIds);
