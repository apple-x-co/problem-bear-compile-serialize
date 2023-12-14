/* product_image_list_by_product_id */
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
WHERE `product_id` = :productId
ORDER BY `group`, `position`;
