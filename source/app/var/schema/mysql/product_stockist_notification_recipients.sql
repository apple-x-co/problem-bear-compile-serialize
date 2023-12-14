DROP TABLE IF EXISTS `product_stockist_notification_recipients`;

CREATE TABLE `product_stockist_notification_recipients`
(
    `id`          INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `product_id`  INTEGER UNSIGNED                NOT NULL COMMENT '商品ID',
    `email`       VARCHAR(255)                    NOT NULL COMMENT 'Eメールアドレス',
    `create_date` DATETIME                        NOT NULL,
    `update_date` DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_product_stockist_notification_recipients_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '商品卸売業者通知';
