DROP TABLE IF EXISTS `order_pickups`;

CREATE TABLE `order_pickups`
(
    `id`                BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `order_id`          BIGINT UNSIGNED                NOT NULL COMMENT '注文ID',
    `pickup_date`       DATE                           NULL COMMENT '受取日',
    `pickup_time`       DATE                           NULL COMMENT '受取時間帯',
    `shop_id`           INTEGER UNSIGNED               NOT NULL COMMENT '店舗ID  (外部キー無し)',
    `shop_name`         VARCHAR(100)                   NOT NULL COMMENT '店舗名',
    `staff_member_id`   INTEGER UNSIGNED               NOT NULL COMMENT 'スタッフID  (外部キー無し)',
    `staff_member_name` VARCHAR(100)                   NOT NULL COMMENT 'スタッフ名',
    `create_date`       DATETIME                       NOT NULL,
    `update_date`       DATETIME                       NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_order_pickups_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '注文受取';
