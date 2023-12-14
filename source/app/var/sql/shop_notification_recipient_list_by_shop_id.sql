/* shop_notification_recipient_list_by_shop_id */
SELECT `id`, `shop_id`, `type`, `email`
FROM shop_notification_recipients
WHERE `shop_id` = :shopId
ORDER BY `type`, `email`;
