DROP TABLE IF EXISTS `product_makers`;

CREATE TABLE `product_makers`
(
    `id`          INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `product_id`  INTEGER UNSIGNED                NOT NULL COMMENT '商品ID',
    `maker_id`    INTEGER UNSIGNED                NOT NULL COMMENT 'メーカーID',
    `position`    SMALLINT UNSIGNED               NOT NULL COMMENT '並び順',
    `create_date` DATETIME                        NOT NULL,
    `update_date` DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_product_makers_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
    CONSTRAINT `fk_product_makers_2` FOREIGN KEY (`maker_id`) REFERENCES `makers` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '商品メーカー';
