DROP TABLE IF EXISTS `payment_methods`;

CREATE TABLE `payment_methods`
(
    `id`          SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `name`        VARCHAR(30)                     NOT NULL COMMENT '名前',
    `key`         VARCHAR(30)                     NOT NULL COMMENT 'キー',
    `role`        VARCHAR(10)                     NOT NULL COMMENT 'seller OR consumer',
    `fee`         INTEGER UNSIGNED                NOT NULL COMMENT '費用',
    `available`   SMALLINT UNSIGNED               NOT NULL COMMENT '利用可能',
    `position`    SMALLINT UNSIGNED               NOT NULL COMMENT '並び順',
    `create_date` DATETIME                        NOT NULL,
    `update_date` DATETIME                        NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '支払い方法';
