DROP TABLE IF EXISTS `store_images`;

CREATE TABLE `store_images`
(
    `id`                 INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `store_id`           INTEGER UNSIGNED                NOT NULL COMMENT 'ECストアID',
    `group`              VARCHAR(10)                     NOT NULL COMMENT 'グループ(hero or logo)',
    `size`               INTEGER UNSIGNED                NOT NULL COMMENT 'ファイルサイズ',
    `media_type`         VARCHAR(30)                     NOT NULL COMMENT 'メディアタイプ',
    `width`              INTEGER UNSIGNED                NOT NULL COMMENT '幅',
    `height`             INTEGER UNSIGNED                NOT NULL COMMENT '高さ',
    `original_file_name` VARCHAR(100)                    NOT NULL COMMENT 'オリジナルファイル名',
    `url`                VARCHAR(255)                    NOT NULL COMMENT 'URL',
    `path`               VARCHAR(255)                    NOT NULL COMMENT 'パス',
    `file_name`          VARCHAR(255)                    NOT NULL COMMENT 'ファイル名',
    `md5`                VARCHAR(64)                     NOT NULL COMMENT 'MD5ハッシュ値',
    `position`           SMALLINT UNSIGNED               NOT NULL COMMENT '並び順',
    `create_date`        DATETIME                        NOT NULL,
    `update_date`        DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_store_images_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = 'ECストア画像';
