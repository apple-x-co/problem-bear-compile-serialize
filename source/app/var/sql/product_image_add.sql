/* product_image_add */
INSERT INTO `product_images` (`id`, `product_id`, `group`, `size`, `media_type`, `width`, `height`,
                              `original_file_name`, `url`, `path`, `file_name`, `md5`, `position`, `create_date`,
                              `update_date`)
VALUES (0, :productId, :group, :size, :mediaType, :width, :height, :originalFileName, :url, :path, :fileName, :md5,
        :position, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
