/* shop_image_item_by_company_id */
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
WHERE EXISTS (SELECT 1
              FROM `shops`
              WHERE `shops`.`id` = `shop_images`.`shop_id`
                AND `shops`.`company_id` = :companyId)
ORDER BY `shop_id`;
