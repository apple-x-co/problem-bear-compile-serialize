/* product_image_item */
SELECT `id`,
       `product_id`,
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
       `position`
FROM `product_images`
WHERE `id` = :id;
