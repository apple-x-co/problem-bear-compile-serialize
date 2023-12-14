DROP TABLE IF EXISTS `companies`;

CREATE TABLE `companies`
(
    `id`                INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `name`              VARCHAR(100)                    NOT NULL COMMENT '名前',
    `seller_slug`       VARCHAR(20)                     NOT NULL COMMENT '販売者スラッグ',
    `seller_url`        VARCHAR(255)                    NOT NULL COMMENT '販売者画面URL',
    `consumer_slug`     VARCHAR(20)                     NOT NULL COMMENT '消費者スラッグ',
    `consumer_url`      VARCHAR(255)                    NOT NULL COMMENT '消費者画面URL',
    `store_id`          INTEGER UNSIGNED                NULL COMMENT 'ECストアID',
    `payment_method_id` SMALLINT UNSIGNED               NULL COMMENT '支払い方法ID',
    `status`            VARCHAR(20)                     NOT NULL COMMENT 'ステータス',
    `leave_date`        DATETIME                        NULL COMMENT '解約日時',
    `void_date`         DATETIME                        NULL COMMENT '無効日時',
    `create_date`       DATETIME                        NOT NULL,
    `update_date`       DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_companies_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`),
    CONSTRAINT `fk_companies_2` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '会社';
