DROP TABLE IF EXISTS `customer_stores`;

CREATE TABLE `customer_stores`
(
    `id`                      INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `customer_id`             INTEGER UNSIGNED                NOT NULL COMMENT '会員ID',
    `store_id`                INTEGER UNSIGNED                NOT NULL COMMENT 'ECストアID (外部キー無し)',
    `shop_id`                 INTEGER UNSIGNED                NULL COMMENT '店舗ID (外部キー無し)',
    `staff_member_id`         INTEGER UNSIGNED                NULL COMMENT 'スタッフID  (外部キー無し)',
    `last_order_date`         DATETIME                        NULL COMMENT '最終注文日時',
    `number_of_orders`        INTEGER UNSIGNED                NOT NULL COMMENT '注文数',
    `number_of_order_cancels` INTEGER UNSIGNED                NOT NULL COMMENT '注文キャンセル数',
    `remaining_point`         INTEGER UNSIGNED                NOT NULL COMMENT '残りポイント',
    `customer_note`           TEXT                            NULL COMMENT '会員ノート (管理者)',
    `create_date`             DATETIME                        NOT NULL,
    `update_date`             DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_customer_stores_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
    UNIQUE KEY `uk_customer_stores_1` (`customer_id`, `store_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '会員ECストア';
