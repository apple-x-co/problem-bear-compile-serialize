DROP TABLE IF EXISTS `store_image_properties`;

CREATE TABLE `store_image_properties`
(
    `id`             INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `store_image_id` INTEGER UNSIGNED                NOT NULL COMMENT 'ECストア画像ID',
    `name`           VARCHAR(100)                    NOT NULL COMMENT '名前',
    `value`          TEXT                            NOT NULL COMMENT '値',
    `create_date`    DATETIME                        NOT NULL,
    `update_date`    DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_store_image_properties_1` FOREIGN KEY (`store_image_id`) REFERENCES `store_images` (`id`),
    UNIQUE KEY `uk_store_image_properties_1` (`store_image_id`, `name`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = 'ECストア画像プロパティ';
