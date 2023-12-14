/* customer_email_item_by_token */
SELECT `id`, `customer_id`, `email`, `token`, `expire_date`, `verified_date`
FROM `customer_emails`
WHERE `token` = :token;
