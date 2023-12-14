/* product_stockist_notification_recipient_add */
INSERT INTO `product_stockist_notification_recipients` (`id`, `product_id`, `email`, `create_date`, `update_date`)
VALUES (0, :productId, :email, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
