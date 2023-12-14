DROP TABLE IF EXISTS `store_fee_payment_intents`;

CREATE TABLE `store_fee_payment_intents`
(
    `id`                     INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `store_usage_billing_id` INTEGER UNSIGNED                NOT NULL COMMENT 'ECストア使用請求ID',
    `payment_method_id`      SMALLINT UNSIGNED                NOT NULL COMMENT '支払い方法ID',
    `idempotency_token`      VARCHAR(100)                    NOT NULL COMMENT '冪等トークン',
    `gateway`                VARCHAR(10)                     NOT NULL COMMENT '支払いゲートウェイ',
    `charge_amount`          INTEGER UNSIGNED                NOT NULL COMMENT '売上請求/オーソリされる金額',
    `capture_amount`         INTEGER UNSIGNED                NOT NULL COMMENT '売上請求された合計金額',
    `refunded_amount`        INTEGER UNSIGNED                NOT NULL COMMENT '返金額',
    `authorization`          VARCHAR(255)                    NULL COMMENT '承認番号',
    `authorized_date`        DATETIME                        NULL COMMENT '承認日時',
    `cancel_date`            DATETIME                        NULL COMMENT 'キャンセル日時',
    `status`                 VARCHAR(20)                     NOT NULL COMMENT 'ステータス',
    `create_date`            DATETIME                        NOT NULL,
    `update_date`            DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_store_fee_payment_intents_1` FOREIGN KEY (`store_usage_billing_id`) REFERENCES `store_usage_billings` (`id`),
    CONSTRAINT `fk_store_fee_payment_intents_2` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = 'ECストア手数料支払い';
