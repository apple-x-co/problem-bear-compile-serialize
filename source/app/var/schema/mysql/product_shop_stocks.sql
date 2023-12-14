DROP TABLE IF EXISTS `product_shop_stocks`;

CREATE TABLE `product_shop_stocks`
(
    `id`          INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `product_id`  INTEGER UNSIGNED                NOT NULL COMMENT '商品ID',
    `shop_id`     INTEGER UNSIGNED                NOT NULL COMMENT '店舗ID',
    `status`      VARCHAR(20)                     NOT NULL COMMENT 'ステータス',
    `create_date` DATETIME                        NOT NULL,
    `update_date` DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_product_shop_stocks_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
    CONSTRAINT `fk_product_shop_stocks_2` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '商品店舗在庫';
