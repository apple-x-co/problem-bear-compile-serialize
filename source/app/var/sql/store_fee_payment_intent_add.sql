/* store_fee_payment_intent_add */
INSERT INTO `store_fee_payment_intents` (`id`, `store_usage_billing_id`, `payment_method_id`, `idempotency_token`,
                                         `gateway`, `charge_amount`, `capture_amount`, `refunded_amount`,
                                         `authorization`, `authorized_date`, `cancel_date`, `status`, `create_date`,
                                         `update_date`)
VALUES (0, :storeUsageBillingId, :paymentMethodId, :idempotencyToken, :gateway, :chargeAmount, :captureAmount,
        :refundedAmount, :authorization, :authorizedDate, :cancelDate, :status, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
