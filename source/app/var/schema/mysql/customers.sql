DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers`
(
    `id`                   INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `family_name`          VARCHAR(50)                     NOT NULL COMMENT '姓',
    `given_name`           VARCHAR(50)                     NOT NULL COMMENT '名',
    `phonetic_family_name` VARCHAR(50)                     NOT NULL COMMENT '読み仮名 姓',
    `phonetic_given_name`  VARCHAR(50)                     NOT NULL COMMENT '読み仮名 名',
    `gender_type`          VARCHAR(10)                     NOT NULL COMMENT '性別',
    `phone_number`         VARCHAR(20)                     NOT NULL COMMENT '電話番号',
    `email`                VARCHAR(255)                    NULL COMMENT 'Eメールアドレス',
    `password`             VARCHAR(255)                    NOT NULL COMMENT 'パスワード',
    `joined_date`          DATETIME                        NOT NULL COMMENT '会員登録日時',
    `status`               VARCHAR(20)                     NOT NULL COMMENT 'ステータス',
    `create_date`          DATETIME                        NOT NULL,
    `update_date`          DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_customers_1` (`email`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '会員';
