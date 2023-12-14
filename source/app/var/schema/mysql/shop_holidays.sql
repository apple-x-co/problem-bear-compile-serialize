DROP TABLE IF EXISTS `shop_holidays`;

CREATE TABLE `shop_holidays`
(
    `id`          INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `shop_id`     INTEGER UNSIGNED                NOT NULL COMMENT '店舗ID',
    `name`        VARCHAR(50)                     NOT NULL COMMENT '名前',
    `date`        DATE                            NOT NULL COMMENT '日付',
    `create_date` DATETIME                        NOT NULL,
    `update_date` DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_shop_holidays_1` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '店舗休日';
