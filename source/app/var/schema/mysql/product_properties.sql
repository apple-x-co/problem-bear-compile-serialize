DROP TABLE IF EXISTS `product_properties`;

CREATE TABLE `product_properties`
(
    `id`          INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `product_id`  INTEGER UNSIGNED                NOT NULL COMMENT '商品ID',
    `name`        VARCHAR(100)                    NOT NULL COMMENT '名前',
    `value`       TEXT                            NOT NULL COMMENT '値',
    `create_date` DATETIME                        NOT NULL,
    `update_date` DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_product_properties_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
    UNIQUE KEY `uk_product_properties_1` (`product_id`, `name`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '商品プロパティ';
