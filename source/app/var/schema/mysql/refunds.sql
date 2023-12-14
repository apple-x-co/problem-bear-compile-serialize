DROP TABLE IF EXISTS `refunds`;

CREATE TABLE `refunds`
(
    `id`              BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `order_id`        BIGINT UNSIGNED                NOT NULL COMMENT '注文ID',
    `refunded_amount` INTEGER UNSIGNED               NOT NULL COMMENT '返金額',
    `status`          VARCHAR(20)                    NOT NULL COMMENT 'ステータス',
    `create_date`     DATETIME                       NOT NULL,
    `update_date`     DATETIME                       NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_refunds_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '払い戻し';
