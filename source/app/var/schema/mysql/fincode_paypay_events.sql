DROP TABLE IF EXISTS `fincode_paypay_events`;

CREATE TABLE `fincode_paypay_events`
(
    `id`               BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `shop_id`          VARCHAR(13)                    NOT NULL COMMENT 'ショップID',
    `access_id`        VARCHAR(24)                    NOT NULL COMMENT '取引ID',
    `order_id`         VARCHAR(30)                    NOT NULL COMMENT 'オーダーID',
    `amount`           INTEGER UNSIGNED               NOT NULL COMMENT '利用金額',
    `tax`              INTEGER UNSIGNED               NOT NULL COMMENT '税送料',
    `customer_id`      VARCHAR(60)                    NOT NULL COMMENT '顧客ID',
    `process_date`     VARCHAR(23)                    NOT NULL COMMENT '処理日時',
    `code_expiry_date` VARCHAR(23)                    NOT NULL COMMENT 'PayPayの支払期限',
    `auth_max_date`    VARCHAR(10)                    NOT NULL COMMENT '仮売上有効期限',
    `code_id`          VARCHAR(64)                    NOT NULL COMMENT 'PayPayの支払コードID',
    `payment_id`       VARCHAR(64)                    NOT NULL COMMENT 'PayPay発番の決済トランザクションID',
    `payment_date`     VARCHAR(23)                    NULL COMMENT '支払日時',
    `error_code`       VARCHAR(20)                    NULL COMMENT 'エラーコード',
    `pay_type`         VARCHAR(10)                    NOT NULL COMMENT '決済種別',
    `event`            VARCHAR(100)                   NOT NULL COMMENT 'Webhookイベントパラメータ',
    `job_cd`           VARCHAR(10)                    NOT NULL COMMENT '処理区分',
    `status`           VARCHAR(30)                    NOT NULL COMMENT '決済ステータス',
    `create_date`      DATETIME                       NOT NULL,
    `update_date`      DATETIME                       NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `ix_fincode_paypay_events_1` (`access_id`),
    INDEX `ix_fincode_paypay_events_2` (`order_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = 'fincodeのPayPayイベント';
