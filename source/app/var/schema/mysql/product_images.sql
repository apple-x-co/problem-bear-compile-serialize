DROP TABLE IF EXISTS `product_images`;

CREATE TABLE `product_images`
(
    `id`                 INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `product_id`         INTEGER UNSIGNED                NOT NULL COMMENT '商品ID',
    `group`              VARCHAR(10)                     NOT NULL COMMENT 'グループ(featured)',
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
    CONSTRAINT `fk_product_images_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '商品画像';
