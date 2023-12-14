DROP TABLE IF EXISTS `areas`;

CREATE TABLE `areas`
(
    `id`          INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `company_id`  INTEGER UNSIGNED                NOT NULL COMMENT '会社ID',
    `name`        VARCHAR(100)                    NOT NULL COMMENT '名前',
    `position`    SMALLINT UNSIGNED               NOT NULL COMMENT '並び順',
    `create_date` DATETIME                        NOT NULL,
    `update_date` DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_areas_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = 'エリア';
