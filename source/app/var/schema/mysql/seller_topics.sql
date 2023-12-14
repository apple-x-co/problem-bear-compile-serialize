DROP TABLE IF EXISTS `seller_topics`;

CREATE TABLE `seller_topics`
(
    `id`                 INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `publish_start_date` DATETIME                        NOT NULL COMMENT '掲載開始日時',
    `publish_end_date`   DATETIME                        NULL COMMENT '掲載終了日時',
    `title`              VARCHAR(100)                    NOT NULL COMMENT 'タイトル',
    `text`               TEXT                            NOT NULL COMMENT '内容',
    `create_date`        DATETIME                        NOT NULL,
    `update_date`        DATETIME                        NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '販売者へのお知らせ';
