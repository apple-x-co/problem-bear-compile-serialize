/* customer_email_add */
INSERT INTO `customer_emails` (`id`, `customer_id`, `email`, `token`, `expire_date`, `verified_date`, `create_date`,
                               `update_date`)
VALUES (0, :customerId, :email, :token, :expireDate, :verifiedDate, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
