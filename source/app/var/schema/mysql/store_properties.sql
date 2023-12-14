DROP TABLE IF EXISTS `store_properties`;

CREATE TABLE `store_properties`
(
    `id`          INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `store_id`    INTEGER UNSIGNED                NOT NULL COMMENT 'ECストアID',
    `name`        VARCHAR(100)                    NOT NULL COMMENT '名前',
    `value`       TEXT                            NOT NULL COMMENT '値',
    `create_date` DATETIME                        NOT NULL,
    `update_date` DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_store_properties_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`),
    UNIQUE KEY `uk_store_properties_1` (`store_id`, `name`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = 'ECストアプロパティ';
