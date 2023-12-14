DROP TABLE IF EXISTS `store_usages`;

CREATE TABLE `store_usages`
(
    `id`          INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `store_id`    INTEGER UNSIGNED               NOT NULL COMMENT 'ECストアID',
    `description` VARCHAR(255)                   NOT NULL COMMENT '説明',
    `target_date` DATE                           NOT NULL COMMENT '対象月',
    `total_price` INTEGER UNSIGNED               NOT NULL COMMENT '合計',
    `status`      VARCHAR(20)                    NOT NULL COMMENT 'ステータス',
    `create_date` DATETIME                       NOT NULL,
    `update_date` DATETIME                       NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_store_usages_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = 'ECストア使用';
