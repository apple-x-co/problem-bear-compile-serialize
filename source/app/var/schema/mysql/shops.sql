DROP TABLE IF EXISTS `shops`;

CREATE TABLE `shops`
(
    `id`          INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `company_id`  INTEGER UNSIGNED                NOT NULL COMMENT '会社ID',
    `area_id`     INTEGER UNSIGNED                NOT NULL COMMENT 'エリアID',
    `name`        VARCHAR(100)                    NOT NULL COMMENT '名前',
    `position`    SMALLINT UNSIGNED               NOT NULL COMMENT '並び順',
    `create_date` DATETIME                        NOT NULL,
    `update_date` DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_shops_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
    CONSTRAINT `fk_shops_2` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '店舗';
