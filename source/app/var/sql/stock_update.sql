/* stock_update */
UPDATE `stocks`
SET `idempotency_token` = :idempotency_token,
    `quantity`          = :quantity,
    `update_date`       = NOW()
WHERE `id` = :id;
