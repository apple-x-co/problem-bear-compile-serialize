DROP TABLE IF EXISTS `customer_emails`;

CREATE TABLE `customer_emails`
(
    `id`            BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `customer_id`   INTEGER UNSIGNED               NOT NULL COMMENT '会員ID',
    `email`         VARCHAR(255)                   NOT NULL COMMENT 'Eメールアドレス',
    `token`         VARCHAR(255)                   NOT NULL COMMENT 'トークン',
    `expire_date`   DATETIME                       NOT NULL COMMENT '有効期限日時',
    `verified_date` DATETIME                       NULL COMMENT '検証済み日時',
    `create_date`   DATETIME                       NOT NULL,
    `update_date`   DATETIME                       NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_customer_emails_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '会員Eメールアドレス';
