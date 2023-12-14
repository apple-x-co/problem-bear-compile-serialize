/* staff_member_image_delete */
INSERT INTO `trash_media_files` (`id`, `table_name`, `identifier`, `original_file_name`, `url`, `path`, `file_name`,
                                 `create_date`)
SELECT 0,
       'staff_member_images',
       `id`,
       `original_file_name`,
       `url`,
       `path`,
       `file_name`,
       NOW()
FROM `staff_member_images`
WHERE `staff_member_id` = :staffMemberId
  AND `id` NOT IN (:aliveIds);

DELETE
FROM `staff_member_images`
WHERE `staff_member_id` = :staffMemberId
AND `id` NOT IN (:aliveIds);
