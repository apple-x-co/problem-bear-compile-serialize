/* shop_image_add */
INSERT INTO `shop_images` (`id`, `shop_id`, `group`, `size`, `media_type`, `width`, `height`, `original_file_name`,
                           `url`, `path`, `file_name`, `md5`, `create_date`, `update_date`)
VALUES (0, :shopId, :group, :size, :mediaType, :width, :height, :originalFileName, :url, :path, :fileName, :md5, NOW(),
        NOW());

SELECT LAST_INSERT_ID() AS id;
