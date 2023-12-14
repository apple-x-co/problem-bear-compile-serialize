DROP TABLE IF EXISTS `customer_points`;

CREATE TABLE `customer_points`
(
    `id`                  BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `customer_reward_id`  INTEGER UNSIGNED               NOT NULL COMMENT '会員リワードID',
    `uuid`                VARCHAR(32)                    NOT NULL COMMENT 'UUID',
    `type`                VARCHAR(10)                    NOT NULL COMMENT 'タイプ',
    `transaction_date`    DATETIME                       NOT NULL COMMENT '取引日時',
    `expire_date`         DATETIME                       NOT NULL COMMENT '有効期限日時',
    `point`               INTEGER UNSIGNED               NOT NULL COMMENT 'ポイント',
    `remaining_point`     INTEGER UNSIGNED               NOT NULL COMMENT '残りポイント',
    `invalidation_date`   DATETIME                       NULL COMMENT '無効化日時',
    `invalidation_reason` VARCHAR(255)                   NULL COMMENT '無効化理由',
    `create_date`         DATETIME                       NOT NULL,
    `update_date`         DATETIME                       NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_customer_points_1` FOREIGN KEY (`customer_reward_id`) REFERENCES `customer_rewards` (`id`),
    UNIQUE KEY `uk_customer_points_1` (`uuid`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '会員ポイント';
