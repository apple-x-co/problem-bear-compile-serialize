DROP TABLE IF EXISTS `store_usage_billings`;

CREATE TABLE `store_usage_billings`
(
    `id`                     INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `store_usage_id`         INTEGER UNSIGNED                NOT NULL COMMENT 'ECストア使用ID',
    `charge_amount`          INTEGER UNSIGNED                NOT NULL COMMENT '請求/オーソリされる金額',
    `billing_date`           DATETIME                        NOT NULL COMMENT '請求日時',
    `scheduled_payment_date` DATETIME                        NOT NULL COMMENT '支払い予定日',
    `due_date`               DATETIME                        NOT NULL COMMENT '支払い期日',
    `paid_date`              DATETIME                        NULL COMMENT '支払い完了日時',
    `family_name`            VARCHAR(50)                     NOT NULL COMMENT '姓',
    `given_name`             VARCHAR(50)                     NOT NULL COMMENT '名',
    `phonetic_family_name`   VARCHAR(50)                     NOT NULL COMMENT '読み仮名 姓',
    `phonetic_given_name`    VARCHAR(50)                     NOT NULL COMMENT '読み仮名 名',
    `postal_code`            VARCHAR(8)                      NOT NULL COMMENT '郵便番号',
    `state`                  VARCHAR(5)                      NOT NULL COMMENT '都道府県',
    `city`                   VARCHAR(20)                     NOT NULL COMMENT '市区町村',
    `address_line_1`         VARCHAR(20)                     NOT NULL COMMENT '番地など',
    `address_line_2`         VARCHAR(20)                     NOT NULL COMMENT 'アパート名,部屋名など',
    `phone_number`           VARCHAR(20)                     NOT NULL COMMENT '電話番号',
    `email`                  VARCHAR(255)                    NOT NULL COMMENT 'Eメールアドレス',
    `status`                 VARCHAR(20)                     NOT NULL COMMENT 'ステータス',
    `create_date`            DATETIME                        NOT NULL,
    `update_date`            DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_store_usage_billings_1` FOREIGN KEY (`store_usage_id`) REFERENCES `store_usages` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = 'ECストア使用請求';
