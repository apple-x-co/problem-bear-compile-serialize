DROP TABLE IF EXISTS `stores`;

CREATE TABLE `stores`
(
    `id`                INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `url`               VARCHAR(255)                    NOT NULL COMMENT 'URL',
    `key`               VARCHAR(20)                     NOT NULL COLLATE 'キー(10文字-20文字)',
    `name`              VARCHAR(100)                    NOT NULL COMMENT '名前',
    `status`            VARCHAR(20)                     NOT NULL COMMENT 'ステータス',
    `leave_date`        DATETIME                        NULL COMMENT '解約日時',
    `void_date`         DATETIME                        NULL COMMENT '無効日時',
    `create_date`       DATETIME                        NOT NULL,
    `update_date`       DATETIME                        NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = 'ECストア';
