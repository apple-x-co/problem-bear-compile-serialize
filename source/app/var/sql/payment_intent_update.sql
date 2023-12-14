/* payment_intent_update */
UPDATE `payment_intents`
SET `capture_amount`  = :captureAmount,
    `authorization`   = :authorization,
    `authorized_date` = :authorizedDate,
    `status`          = :status,
    `update_date`     = NOW()
WHERE `id` = :id;
