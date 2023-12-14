DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders`
(
    `id`                   BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `store_id`             INTEGER UNSIGNED               NOT NULL COMMENT 'ECストアID',
    `order_no`             VARCHAR(50)                    NOT NULL COMMENT '注文番号',
    `order_date`           DATETIME                       NOT NULL COMMENT '注文日時',
    `close_date`           DATETIME                       NULL COMMENT 'アーカイブ日時',
    `family_name`          VARCHAR(50)                    NOT NULL COMMENT '姓',
    `given_name`           VARCHAR(50)                    NOT NULL COMMENT '名',
    `phonetic_family_name` VARCHAR(50)                    NOT NULL COMMENT '読み仮名 姓',
    `phonetic_given_name`  VARCHAR(50)                    NOT NULL COMMENT '読み仮名 名',
    `postal_code`          VARCHAR(8)                     NOT NULL COMMENT '郵便番号',
    `state`                VARCHAR(5)                     NOT NULL COMMENT '都道府県',
    `city`                 VARCHAR(20)                    NOT NULL COMMENT '市区町村',
    `address_line_1`       VARCHAR(20)                    NOT NULL COMMENT '番地など',
    `address_line_2`       VARCHAR(20)                    NOT NULL COMMENT 'アパート名,部屋名など',
    `phone_number`         VARCHAR(20)                    NOT NULL COMMENT '電話番号',
    `email`                VARCHAR(255)                   NOT NULL COMMENT 'Eメールアドレス',
    `discount_code`        VARCHAR(50)                    NULL COMMENT '割引コード',
    `discount_price`       INTEGER                        NULL COMMENT '割引額 (マイナス値)',
    `point_rate`           INTEGER UNSIGNED               NULL COMMENT 'ポイント率',
    `spending_point`       INTEGER                        NULL COMMENT '消費ポイント数 (マイナス値)',
    `earning_point`        INTEGER UNSIGNED               NULL COMMENT '獲得ポイント数',
    `total_price`          INTEGER UNSIGNED               NOT NULL COMMENT '合計',
    `total_tax`            INTEGER UNSIGNED               NOT NULL COMMENT '消費税額',
    `subtotal_price`       INTEGER UNSIGNED               NOT NULL COMMENT '商品合計',
    `payment_method_id`    INTEGER UNSIGNED               NOT NULL COMMENT '支払い方法ID (外部キー無し)',
    `payment_method_name`  VARCHAR(50)                    NOT NULL COMMENT '支払い方法名',
    `payment_fee`          INTEGER UNSIGNED               NOT NULL COMMENT '支払い手数料',
    `note`                 TEXT                           NULL COMMENT 'ノート (会員)',
    `order_note`           TEXT                           NULL COMMENT '注文ノート (管理者)',
    `pickup_status`        VARCHAR(20)                    NOT NULL COMMENT '受取ステータス',
    `status`               VARCHAR(20)                    NOT NULL COMMENT 'ステータス',
    `create_date`          DATETIME                       NOT NULL,
    `update_date`          DATETIME                       NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_orders_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '注文';