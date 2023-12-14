DROP TABLE IF EXISTS `shop_regular_holidays`;

CREATE TABLE `shop_regular_holidays`
(
    `id`          INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `shop_id`     INTEGER UNSIGNED                NOT NULL COMMENT '店舗ID',
    `day_of_week` VARCHAR(10)                     NOT NULL COMMENT '曜日',
    `create_date` DATETIME                        NOT NULL,
    `update_date` DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_shop_regular_holidays_1` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '店舗定休日';
