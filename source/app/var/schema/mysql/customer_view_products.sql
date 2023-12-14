DROP TABLE IF EXISTS `customer_view_products`;

CREATE TABLE `customer_view_products`
(
    `id`          BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `customer_id` INTEGER UNSIGNED               NOT NULL COMMENT '会員ID',
    `store_id`    INTEGER UNSIGNED               NOT NULL COMMENT 'ECストアID (外部キー無し)',
    `product_id`  INTEGER UNSIGNED               NOT NULL COMMENT '商品ID (外部キー無し)',
    `viewed_date` DATETIME                       NOT NULL COMMENT 'お気に入り登録日時',
    `create_date` DATETIME                       NOT NULL,
    `update_date` DATETIME                       NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_customer_view_products_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '会員が最近見た商品';
