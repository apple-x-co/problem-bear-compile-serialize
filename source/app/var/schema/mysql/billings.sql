DROP TABLE IF EXISTS `billings`;

CREATE TABLE `billings`
(
    `id`                   BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `order_id`             BIGINT UNSIGNED                NOT NULL COMMENT '注文ID',
    `billing_no`           VARCHAR(50)                    NOT NULL COMMENT '請求No',
    `charge_amount`        INTEGER UNSIGNED               NOT NULL COMMENT '売上請求/オーソリされる金額',
    `billing_date`         DATETIME                       NOT NULL COMMENT '請求日時',
    `family_name`          VARCHAR(50)                    NOT NULL COMMENT '姓',
    `given_name`           VARCHAR(50)                    NOT NULL COMMENT '名',
    `phonetic_family_name` VARCHAR(50)                    NOT NULL COMMENT '読み仮名 姓',
    `phonetic_given_name`  VARCHAR(50)                    NOT NULL COMMENT '読み仮名 名',
    `postal_code`          VARCHAR(8)                     NOT NULL COMMENT '郵便番号',
    `state`                VARCHAR(5)                     NOT NULL COMMENT '都道府県',
    `city`                 VARCHAR(20)                    NOT NULL COMMENT '市区町村',
    `address_line_1`       VARCHAR(20)                    NOT NULL COMMENT '番地など',
    `address_line_2`       VARCHAR(20)                    NOT NULL COMMENT 'アパート名,部屋名など',
    `phone_number`         VARCHAR(20)                    NOT NULL COMMENT '電話番号',
    `email`                VARCHAR(255)                   NOT NULL COMMENT 'Eメールアドレス',
    `create_date`          DATETIME                       NOT NULL,
    `update_date`          DATETIME                       NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_billings_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '請求';
