/* product_image_delete_old */
INSERT INTO `trash_media_files` (`id`, `table_name`, `identifier`, `original_file_name`, `url`, `path`, `file_name`,
                                 `create_date`)
SELECT 0,
       'product_images',
       `id`,
       `original_file_name`,
       `url`,
       `path`,
       `file_name`,
       NOW()
FROM `product_images`
WHERE `product_id` = :productId
  AND `group` = :group,
  AND `id` NOT IN (:aliveIds);

DELETE
FROM `product_images`
WHERE `product_id` = :productId
  AND `group` = :group,
  AND `id` NOT IN (:aliveIds);
