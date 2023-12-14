DROP TABLE IF EXISTS `company_properties`;

CREATE TABLE `company_properties`
(
    `id`          INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `company_id`  INTEGER UNSIGNED                NOT NULL COMMENT '会社ID',
    `name`        VARCHAR(100)                    NOT NULL COMMENT '名前',
    `value`       TEXT                            NOT NULL COMMENT '値',
    `create_date` DATETIME                        NOT NULL,
    `update_date` DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_company_properties_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
    UNIQUE KEY `uk_company_properties_1` (`company_id`, `name`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '会社プロパティ';
