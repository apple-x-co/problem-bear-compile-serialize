/* store_fee_payment_intent_item */
SELECT `id`,
       `store_usage_billing_id`,
       `payment_method_id`,
       `idempotency_token`,
       `gateway`,
       `charge_amount`,
       `capture_amount`,
       `refunded_amount`,
       `authorization`,
       `authorized_date`,
       `cancel_date`,
       `status`
FROM `store_fee_payment_intents`
WHERE `id` = :id;
