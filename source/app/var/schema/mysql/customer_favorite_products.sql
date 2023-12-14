DROP TABLE IF EXISTS `customer_favorite_products`;

CREATE TABLE `customer_favorite_products`
(
    `id`             BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `customer_id`    INTEGER UNSIGNED               NOT NULL COMMENT '会員ID',
    `store_id`       INTEGER UNSIGNED               NOT NULL COMMENT 'ECストアID (外部キー無し)',
    `product_id`     INTEGER UNSIGNED               NOT NULL COMMENT '商品ID (外部キー無し)',
    `favorited_date` DATETIME                       NOT NULL COMMENT 'お気に入り登録日時',
    `create_date`    DATETIME                       NOT NULL,
    `update_date`    DATETIME                       NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_customer_favorite_products_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
    UNIQUE KEY `uk_customer_favorite_products_1` (`customer_id`, `product_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '会員のお気に入り商品';
