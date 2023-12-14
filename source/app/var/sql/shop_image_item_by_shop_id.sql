/* shop_image_item_by_shop_id */
SELECT `id`,
       `shop_id`,
       `group`,
       `size`,
       `media_type`,
       `width`,
       `height`,
       `original_file_name`,
       `url`,
       `path`,
       `file_name`,
       `md5`
FROM `shop_images`
WHERE `shop_id` = :shopId;
