DROP TABLE IF EXISTS `cart_items`;

CREATE TABLE `cart_items`
(
    `id`                 BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `cart_id`            BIGINT UNSIGNED                NOT NULL COMMENT 'カートID',
    `product_id`         INTEGER UNSIGNED               NOT NULL COMMENT '商品ID (外部キー無し)',
    `product_variant_id` INTEGER UNSIGNED               NOT NULL COMMENT '商品バリエーションID (外部キー無し)',
    `title`              VARCHAR(100)                   NOT NULL COMMENT 'タイトル',
    `marker_name`        VARCHAR(100)                   NOT NULL COMMENT 'メーカー名',
    `taxonomy_name`      VARCHAR(100)                   NOT NULL COMMENT '分類名',
    `quantity`           SMALLINT UNSIGNED              NOT NULL COMMENT '数量',
    `price`              SMALLINT UNSIGNED              NOT NULL COMMENT '税抜き価格',
    `tax_rate`           SMALLINT UNSIGNED              NOT NULL COMMENT '消費税率(%)',
    `point`              SMALLINT UNSIGNED              NULL COMMENT 'ポイント',
    `create_date`        DATETIME                       NOT NULL,
    `update_date`        DATETIME                       NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_cart_items_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = 'ショッピングカートアイテム';
