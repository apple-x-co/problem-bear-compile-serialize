DROP TABLE IF EXISTS `shop_properties`;

CREATE TABLE `shop_properties`
(
    `id`          INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `shop_id`     INTEGER UNSIGNED                NOT NULL COMMENT '店舗ID',
    `name`        VARCHAR(100)                    NOT NULL COMMENT '名前',
    `value`       TEXT                            NOT NULL COMMENT '値',
    `create_date` DATETIME                        NOT NULL,
    `update_date` DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_shop_properties_1` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`),
    UNIQUE KEY `uk_shop_properties_1` (`shop_id`, `name`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '店舗プロパティ';
