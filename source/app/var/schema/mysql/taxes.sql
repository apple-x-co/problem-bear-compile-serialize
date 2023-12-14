DROP TABLE IF EXISTS `taxes`;

CREATE TABLE `taxes`
(
    `id`          INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `name`        VARCHAR(100)                    NOT NULL COMMENT '名前',
    `rate`        INTEGER UNSIGNED                NOT NULL COMMENT '税率',
    `create_date` DATETIME                        NOT NULL,
    `update_date` DATETIME                        NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '税率';
