/* customer_store_add */
INSERT INTO `customer_stores` (`id`, `customer_id`, `store_id`, `shop_id`, `staff_member_id`, `last_order_date`,
                               `number_of_orders`, `number_of_order_cancels`, `remaining_point`, `customer_note`,
                               `create_date`, `update_date`)
VALUES (0, :customerId, :storeId, :shopId, :staffMemberId, :lastOrderDate, :numberOfOrders, :numberOfOrderCancels,
        :remainingPoint, :customerNote, NOW(), NOW())

SELECT LAST_INSERT_ID() AS id;
