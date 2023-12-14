/* product_image_list_by_store_id */
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
WHERE EXISTS (SELECT 1
              FROM `products`
              WHERE `products`.`id` = `product_images`.`product_id`
                AND `products`.`store_id` = :storeId)
ORDER BY `group`, `position`;
