/* customer_order_item_by_order_id */
SELECT `id`, `customer_id`, `store_id`, `order_id`
FROM `customer_orders`
WHERE `order_id` = :orderId;
