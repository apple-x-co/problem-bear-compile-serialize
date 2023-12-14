DROP TABLE IF EXISTS `product_purposes`;

CREATE TABLE `product_purposes`
(
    `id`          INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `product_id`  INTEGER UNSIGNED                NOT NULL COMMENT '商品ID',
    `purpose_id`  INTEGER UNSIGNED                NOT NULL COMMENT '目的ID',
    `position`    SMALLINT UNSIGNED               NOT NULL COMMENT '並び順',
    `create_date` DATETIME                        NOT NULL,
    `update_date` DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_product_purposes_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
    CONSTRAINT `fk_product_purposes_2` FOREIGN KEY (`purpose_id`) REFERENCES `purposes` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '商品目的';
