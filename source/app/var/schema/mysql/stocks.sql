DROP TABLE IF EXISTS `stocks`;

CREATE TABLE `stocks`
(
    `id`                 INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `store_id`           INTEGER UNSIGNED                NOT NULL COMMENT '店舗ID',
    `product_variant_id` INTEGER UNSIGNED                NOT NULL COMMENT '商品バリエーションID',
    `idempotency_token`  VARCHAR(100)                    NOT NULL COMMENT '冪等トークン',
    `quantity`           SMALLINT UNSIGNED               NOT NULL COMMENT '数量',
    `create_date`        DATETIME                        NOT NULL,
    `update_date`        DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_stocks_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`),
    CONSTRAINT `fk_stocks_2` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '在庫';
