/* customer_order_add */
INSERT INTO `customer_orders` (`id`, `customer_id`, `store_id`, `order_id`, `create_date`, `update_date`)
VALUES (0, :customerId, :storeId, :orderId, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
