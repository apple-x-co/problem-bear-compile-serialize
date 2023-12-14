DROP TABLE IF EXISTS `shop_notification_recipients`;

CREATE TABLE `shop_notification_recipients`
(
    `id`          INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `shop_id`     INTEGER UNSIGNED                NOT NULL COMMENT '店舗ID',
    `type`        VARCHAR(10)                     NOT NULL COMMENT 'タイプ',
    `email`       VARCHAR(255)                    NOT NULL COMMENT 'Eメールアドレス',
    `create_date` DATETIME                        NOT NULL,
    `update_date` DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_shop_notification_recipients_1` FOREIGN KEY (`shop_id`) REFERENCES `products` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '店舗通知';
