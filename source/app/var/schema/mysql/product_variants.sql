DROP TABLE IF EXISTS `product_variants`;

CREATE TABLE `product_variants`
(
    `id`                   INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `product_id`           INTEGER UNSIGNED                NOT NULL COMMENT '商品ID',
    `title`                VARCHAR(100)                    NOT NULL COMMENT '商品名',
    `code`                 VARCHAR(100)                    NOT NULL COMMENT '商品コード',
    `sku`                  VARCHAR(100)                    NOT NULL COMMENT 'SKU',
    `original_price`       INTEGER UNSIGNED                NOT NULL COMMENT '値引前価格(税抜き)',
    `price`                INTEGER UNSIGNED                NOT NULL COMMENT '販売価格(税抜き)',
    `discount_price`       INTEGER                         NULL COMMENT '値引額 (マイナス値)',
    `pickup_duration_days` INTEGER UNSIGNED                NOT NULL COMMENT '受取可能日数',
    `create_date`          DATETIME                        NOT NULL,
    `update_date`          DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_product_variants_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '商品バリエーション';
