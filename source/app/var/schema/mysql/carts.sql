DROP TABLE IF EXISTS `carts`;

CREATE TABLE `carts`
(
    `id`          BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `token`       VARCHAR(100)                    NULL COMMENT 'トークン',
    `customer_id` INTEGER UNSIGNED                NULL COMMENT '会員ID (外部キー無し)',
    `store_id`    INTEGER UNSIGNED                NOT NULL COMMENT 'ECストアID (外部キー無し)',
    `note`        TEXT                            NULL COMMENT 'ノート (会員)',
    `create_date` DATETIME                        NOT NULL,
    `update_date` DATETIME                        NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = 'ショッピングカート';
