/* store_usage_billing_update */
UPDATE `store_usage_billings`
SET `paid_date`   = :paidDate,
    `status`      = :status,
    `update_date` = :updateDate
WHERE `id` = :id;
