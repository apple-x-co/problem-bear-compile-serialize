DROP TABLE IF EXISTS `trash_media_files`;

CREATE TABLE `trash_media_files`
(
    `id`                 INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `table_name`         VARCHAR(50)                     NOT NULL COMMENT 'テーブル名',
    `identifier`         VARCHAR(50)                     NOT NULL COMMENT '識別子',
    `original_file_name` VARCHAR(100)                    NOT NULL COMMENT 'オリジナルファイル名',
    `url`                VARCHAR(255)                    NOT NULL COMMENT 'URL',
    `path`               VARCHAR(255)                    NOT NULL COMMENT 'パス',
    `file_name`          VARCHAR(255)                    NOT NULL COMMENT 'ファイル名',
    `create_date`        DATETIME                        NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = 'メディアファイルゴミ箱';
