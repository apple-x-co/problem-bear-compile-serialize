/* shop_notification_recipient_add */
INSERT INTO `shop_notification_recipients` (`id`, `shop_id`, `type`, `email`, `create_date`, `update_date`)
VALUES (0, :shopId, :type, :email, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
