/* refund_add */
INSERT INTO `refunds` (`id`, `order_id`, `refunded_amount`, `status`, `create_date`, `update_date`)
VALUES (0, :orderId, :refundedAmount, :status, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
