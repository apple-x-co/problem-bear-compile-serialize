DROP TABLE IF EXISTS `staff_member_login_tokens`;

CREATE TABLE `staff_member_login_tokens`
(
    `id`              BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `staff_member_id` INTEGER UNSIGNED               NOT NULL COMMENT 'スタッフID',
    `token`           VARCHAR(100)                   NOT NULL COMMENT 'ログイントークン',
    `expire_date`     DATETIME                       NOT NULL COMMENT '有効期限日時',
    `created_date`    DATETIME                       NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_staff_member_login_tokens_1` FOREIGN KEY (`staff_member_id`) REFERENCES `staff_members` (`id`),
    INDEX `ix_staff_member_login_tokens_1` (`token`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = 'スタッフ永続ログイントークン';
