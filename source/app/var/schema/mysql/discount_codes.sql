DROP TABLE IF EXISTS `discount_codes`;

CREATE TABLE `discount_codes`
(
    `id`                INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `store_id`          INTEGER UNSIGNED                NOT NULL COMMENT 'ECストアID',
    `title`             VARCHAR(100)                    NOT NULL COMMENT 'タイトル',
    `code`              VARCHAR(50)                     NOT NULL COMMENT 'コード',
    `type`              VARCHAR(20)                     NOT NULL COMMENT '値引きタイプ',
    `value`             INTEGER UNSIGNED                NOT NULL COMMENT '値引き値',
    `start_date`        DATETIME                        NULL COMMENT '利用可能開始日時',
    `end_date`          DATETIME                        NULL COMMENT '利用可能終了日時',
    `usage_count`       INTEGER UNSIGNED                NOT NULL COMMENT '利用済み回数',
    `usage_limit`       INTEGER UNSIGNED                NULL COMMENT '利用制限回数',
    `minimum_price`     INTEGER UNSIGNED                NULL COMMENT '利用可能最低金額',
    `once_per_customer` SMALLINT UNSIGNED               NOT NULL COMMENT '1人1回制限',
    `target_selection`  VARCHAR(20)                     NOT NULL COMMENT '割引対象商品の選択方法',
    `status`            VARCHAR(20)                     NOT NULL COMMENT 'ステータス',
    `create_date`       DATETIME                        NOT NULL,
    `update_date`       DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_discount_codes_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`),
    UNIQUE KEY `uk_discount_codes_1` (`store_id`, `code`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '割引コード';
