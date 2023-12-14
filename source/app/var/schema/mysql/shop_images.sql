DROP TABLE IF EXISTS `shop_images`;

CREATE TABLE `shop_images`
(
    `id`                 INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `shop_id`            INTEGER UNSIGNED                NOT NULL COMMENT '店舗ID',
    `group`              VARCHAR(10)                     NOT NULL COMMENT 'グループ(exterior)',
    `size`               INTEGER UNSIGNED                NOT NULL COMMENT 'ファイルサイズ',
    `media_type`         VARCHAR(30)                     NOT NULL COMMENT 'メディアタイプ',
    `width`              INTEGER UNSIGNED                NOT NULL COMMENT '幅',
    `height`             INTEGER UNSIGNED                NOT NULL COMMENT '高さ',
    `original_file_name` VARCHAR(100)                    NOT NULL COMMENT 'オリジナルファイル名',
    `url`                VARCHAR(255)                    NOT NULL COMMENT 'URL',
    `path`               VARCHAR(255)                    NOT NULL COMMENT 'パス',
    `file_name`          VARCHAR(255)                    NOT NULL COMMENT 'ファイル名',
    `md5`                VARCHAR(64)                     NOT NULL COMMENT 'MD5ハッシュ値',
    `create_date`        DATETIME                        NOT NULL,
    `update_date`        DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_shop_images_1` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '店舗画像';
