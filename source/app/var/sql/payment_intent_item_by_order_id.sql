/* payment_intent_item_by_order_id */
SELECT `id`,
       `order_id`,
       `billing_id`,
       `payment_method_id`,
       `idempotency_token`,
       `gateway`,
       `charge_amount`,
       `capture_amount`,
       `authorization`,
       `authorized_date`,
       `status`
FROM `payment_intents`
WHERE `order_id` = :orderId;
