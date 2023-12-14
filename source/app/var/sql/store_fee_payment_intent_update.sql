/* store_fee_payment_intent_update */
UPDATE `store_fee_payment_intents`
SET `payment_method_id` = :paymentMethodId,
    `idempotency_token` = :idempotencyToken,
    `gateway`           = :gateway,
    `charge_amount`     = :chargeAmount,
    `capture_amount`    = :captureAmount,
    `refunded_amount`   = :refundedAmount,
    `authorization`     = :authorization,
    `authorized_date`   = :authorizedDate,
    `cancel_date`       = :cancelDate,
    `status`            = :status
WHERE `id` = :id;
