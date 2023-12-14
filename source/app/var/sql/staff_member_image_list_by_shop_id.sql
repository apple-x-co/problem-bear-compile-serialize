/* staff_member_image_list_by_shop_id */
SELECT `id`,
       `staff_member_id`,
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
FROM `staff_member_images`
WHERE EXISTS (SELECT 1
              FROM `staff_members`
              WHERE `staff_members`.`id` = `staff_member_images`.`staff_member_id`
                AND `staff_members`.`shop_id` = :shopId)
ORDER BY `staff_member_id`;
