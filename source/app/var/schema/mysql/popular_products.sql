DROP TABLE IF EXISTS `popular_products`;

CREATE TABLE `popular_products`
(
    `id`          INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `store_id`    INTEGER UNSIGNED                NOT NULL COMMENT 'ECストアID',
    `product_id`  INTEGER UNSIGNED                NOT NULL COMMENT '商品ID',
    `position`    SMALLINT UNSIGNED               NOT NULL COMMENT '並び順',
    `create_date` DATETIME                        NOT NULL,
    `update_date` DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_popular_products_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`),
    CONSTRAINT `fk_popular_products_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '売れ筋の商品';
