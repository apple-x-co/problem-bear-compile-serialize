/* store_fee_payment_intent_list_by_store_usage_id */
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
WHERE EXISTS (SELECT 1
              FROM `store_usage_billings`
              WHERE `store_usage_billings`.`id` = `store_fee_payment_intents`.`store_usage_billing_id`
                AND `store_usage_billings`.`store_usage_id` = :storeUsageId);
