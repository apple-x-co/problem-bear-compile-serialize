/* staff_member_image_add */
INSERT INTO `staff_member_images` (`id`, `staff_member_id`, `group`, `size`, `media_type`, `width`, `height`,
                                   `original_file_name`, `url`, `path`, `file_name`, `md5`, `create_date`,
                                   `update_date`)
VALUES (0, :staffMemberId, :group, :size, :mediaType, :width, :height, :originalFileName, :url, :path, :fileName, :md5,
        NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
