/* refund_item_by_order_id */
SELECT `id`, `order_id`, `refunded_amount`, `status`
FROM `refunds`
WHERE `order_id` = :orderId;
