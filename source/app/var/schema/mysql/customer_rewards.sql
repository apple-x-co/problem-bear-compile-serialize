DROP TABLE IF EXISTS `customer_rewards`;

CREATE TABLE `customer_rewards`
(
    `id`              INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `customer_id`     INTEGER UNSIGNED               NOT NULL COMMENT '会員ID',
    `store_id`        INTEGER UNSIGNED               NOT NULL COMMENT 'ECストアID (外部キー無し)',
    `remaining_point` INTEGER UNSIGNED               NOT NULL COMMENT '残りポイント',
    `create_date`     DATETIME                       NOT NULL,
    `update_date`     DATETIME                       NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_customer_rewards_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
    UNIQUE KEY `uk_customer_rewards_1` (`customer_id`, `store_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '会員リワード';
