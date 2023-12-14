DROP TABLE IF EXISTS `staff_member_permissions`;

CREATE TABLE `staff_member_permissions`
(
    `id`              INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `staff_member_id` INTEGER UNSIGNED                NOT NULL COMMENT 'スタッフID',
    `access`          VARCHAR(10)                     NOT NULL COMMENT 'アクセス設定',
    `resource_name`   VARCHAR(64)                     NOT NULL COMMENT 'リソース名',
    `permission`      VARCHAR(10)                     NOT NULL COMMENT 'パーミッション',
    `create_date`     DATETIME                        NOT NULL,
    `update_date`     DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_staff_member_permissions_1` FOREIGN KEY (`staff_member_id`) REFERENCES `shops` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = 'スタッフ権限';
