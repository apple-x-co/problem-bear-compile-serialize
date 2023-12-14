DROP TABLE IF EXISTS `staff_members`;

CREATE TABLE `staff_members`
(
    `id`          INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `company_id`  INTEGER UNSIGNED                NOT NULL COMMENT '会社ID',
    `shop_id`     INTEGER UNSIGNED                NULL COMMENT '店舗ID',
    `name`        VARCHAR(100)                    NOT NULL COMMENT '名前',
    `code`        VARCHAR(100)                    NOT NULL COMMENT 'コード',
    `email`       VARCHAR(255)                    NULL COMMENT 'Eメールアドレス',
    `password`    VARCHAR(255)                    NOT NULL COMMENT 'パスワード',
    `message`     VARCHAR(255)                    NOT NULL COMMENT 'メッセージ',
    `position`    SMALLINT UNSIGNED               NOT NULL COMMENT '並び順',
    `status`      VARCHAR(20)                     NOT NULL COMMENT 'ステータス',
    `create_date` DATETIME                        NOT NULL,
    `update_date` DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_staff_members_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
    CONSTRAINT `fk_staff_members_2` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = 'スタッフ';
