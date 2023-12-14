DROP TABLE IF EXISTS `purposes`;

CREATE TABLE `purposes`
(
    `id`          INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `store_id`    INTEGER UNSIGNED                NOT NULL COMMENT 'ECストアID',
    `parent_id`   INTEGER UNSIGNED                NULL COMMENT '親ID',
    `name`        VARCHAR(100)                    NOT NULL COMMENT '名前',
    `position`    SMALLINT UNSIGNED               NOT NULL COMMENT '並び順',
    `create_date` DATETIME                        NOT NULL,
    `update_date` DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_purposes_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`),
    CONSTRAINT `fk_purposes_2` FOREIGN KEY (`parent_id`) REFERENCES `purposes` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '目的';
