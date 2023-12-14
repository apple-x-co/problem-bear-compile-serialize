DROP TABLE IF EXISTS `products`;

CREATE TABLE `products`
(
    `id`                            INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `store_id`                      INTEGER UNSIGNED                NOT NULL COMMENT 'ECストアID',
    `title`                         VARCHAR(100)                    NOT NULL COMMENT '商品名',
    `code`                          VARCHAR(100)                    NOT NULL COMMENT '商品コード',
    `taxable`                       SMALLINT UNSIGNED               NOT NULL COMMENT '課税対象',
    `tax_id`                        INTEGER UNSIGNED                NULL COMMENT '税率ID',
    `original_price`                INTEGER UNSIGNED                NOT NULL COMMENT '値引前価格(税抜き)',
    `price`                         INTEGER UNSIGNED                NOT NULL COMMENT '販売価格(税抜き)',
    `number_of_pieces`              INTEGER UNSIGNED                NOT NULL COMMENT '個数',
    `discount_rate`                 INTEGER UNSIGNED                NULL COMMENT '値引率',
    `discount_price`                INTEGER                         NULL COMMENT '値引額 (マイナス値)',
    `stockist_notification_enabled` SMALLINT UNSIGNED               NOT NULL COMMENT '卸売業者通知',
    `stockist_name`                 VARCHAR(100)                    NULL COMMENT '卸売業者名',
    `sale_start_date`               DATETIME                        NULL COMMENT '販売開始日時',
    `sale_end_date`                 DATETIME                        NULL COMMENT '販売終了日時',
    `status`                        VARCHAR(20)                     NOT NULL COMMENT 'ステータス',
    `create_date`                   DATETIME                        NOT NULL,
    `update_date`                   DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_products_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`),
    CONSTRAINT `fk_products_2` FOREIGN KEY (`tax_id`) REFERENCES `taxes` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '商品';
