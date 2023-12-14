DROP TABLE IF EXISTS `admin_login_tokens`;

CREATE TABLE `admin_login_tokens`
(
    `id`           BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `admin_id`     INTEGER UNSIGNED               NOT NULL COMMENT '管理者ID',
    `token`        VARCHAR(100)                   NOT NULL COMMENT 'ログイントークン',
    `expire_date`  DATETIME                       NOT NULL COMMENT '有効期限日時',
    `created_date` DATETIME                       NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_admin_login_tokens_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
    INDEX `ix_admin_login_tokens_1` (`token`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '管理者永続ログイントークン';
