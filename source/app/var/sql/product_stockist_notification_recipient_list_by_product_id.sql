/* product_stockist_notification_recipient_list_by_product_id */
SELECT `id`, `product_id`, `email`
FROM `product_stockist_notification_recipients`
WHERE `product_id` = :productId;
