DROP TABLE IF EXISTS `order_line_items`;

CREATE TABLE `order_line_items`
(
    `id`                  BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `order_id`            BIGINT UNSIGNED                NOT NULL COMMENT '注文ID',
    `product_id`          INTEGER UNSIGNED               NOT NULL COMMENT '商品ID (外部キー無し)',
    `product_variant_id`  INTEGER UNSIGNED               NOT NULL COMMENT '商品バリエーションID (外部キー無し)',
    `title`               VARCHAR(100)                   NOT NULL COMMENT '商品名',
    `maker_name`          VARCHAR(100)                   NOT NULL COMMENT 'メーカー名',
    `taxonomy_name`       VARCHAR(100)                   NOT NULL COMMENT '分類名',
    `discount_price`      INTEGER                        NULL COMMENT '値引額 (マイナス値)',
    `original_price`      INTEGER UNSIGNED               NOT NULL COMMENT '値引き前単価(税抜き)',
    `original_tax`        INTEGER UNSIGNED               NOT NULL COMMENT '値引き前消費税額',
    `original_line_price` INTEGER UNSIGNED               NOT NULL COMMENT '値引き前金額 ((単価+消費税)*数量)',
    `final_price`         INTEGER UNSIGNED               NOT NULL COMMENT '値引き後単価(税抜き)',
    `final_tax`           INTEGER UNSIGNED               NOT NULL COMMENT '値引き後消費税額',
    `final_line_price`    INTEGER UNSIGNED               NOT NULL COMMENT '値引き後金額 ((単価+消費税)*数量)',
    `tax_rate`            INTEGER UNSIGNED               NOT NULL COMMENT '消費税率(%)',
    `quantity`            INTEGER UNSIGNED               NOT NULL COMMENT '数量',
    `create_date`         DATETIME                       NOT NULL,
    `update_date`         DATETIME                       NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_order_line_items_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '注文商品';
