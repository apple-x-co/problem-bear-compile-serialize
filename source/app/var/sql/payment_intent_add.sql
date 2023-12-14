/* payment_intent_add */
INSERT INTO `payment_intents` (`id`, `order_id`, `billing_id`, `payment_method_id`, `idempotency_token`, `gateway`,
                               `charge_amount`, `capture_amount`, `authorization`, `authorized_date`, `status`,
                               `create_date`, `update_date`)
VALUES (0, :orderId, :billingId, :paymentMethodId, :idempotencyToken, :gateway, :chargeAmount, :captureAmount,
        :authorization, :authorizedDate, :status, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
