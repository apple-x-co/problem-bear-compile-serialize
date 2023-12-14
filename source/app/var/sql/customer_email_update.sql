/* customer_email_update */
UPDATE `customer_emails`
SET `verified_date` = :verifiedDate,
    `update_date`   = :updateDate
WHERE `id` = :id;
