DROP TABLE IF EXISTS `discount_code_activities`;

CREATE TABLE `discount_code_activities`
(
    `id`           INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `store_id`     INTEGER UNSIGNED                NOT NULL COMMENT 'ECストアID (外部キー無し)',
    `code`         VARCHAR(50)                     NOT NULL COMMENT 'コード',
    `customer_id`  INTEGER UNSIGNED                NULL COMMENT '会員ID (外部キー無し)',
    `email`        VARCHAR(255)                    NOT NULL COMMENT 'Eメールアドレス',
    `phone_number` VARCHAR(20)                     NOT NULL COMMENT '電話番号',
    `used_date`    DATETIME                        NOT NULL COMMENT '使用日時',
    `create_date`  DATETIME                        NOT NULL,
    `update_date`  DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `ix_discount_code_activities_1` (`code`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '割引コードの履歴';
