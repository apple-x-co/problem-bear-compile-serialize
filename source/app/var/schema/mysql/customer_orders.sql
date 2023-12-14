DROP TABLE IF EXISTS `customer_orders`;

CREATE TABLE `customer_orders`
(
    `id`             INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `customer_id`    INTEGER UNSIGNED                NOT NULL COMMENT '会員ID',
    `store_id`       INTEGER UNSIGNED                NOT NULL COMMENT 'ECストアID (外部キー無し)',
    `order_id`       BIGINT UNSIGNED                 NOT NULL COMMENT '注文ID',
    `create_date`    DATETIME                        NOT NULL,
    `update_date`    DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_customer_orders_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
    CONSTRAINT `fk_customer_orders_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '会員の注文';
