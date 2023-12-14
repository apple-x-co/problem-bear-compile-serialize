/* staff_member_image_by_staff_member_id */
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
WHERE `staff_member_id` = :staffMemberId;
