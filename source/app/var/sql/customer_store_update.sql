/* customer_store_update */
UPDATE `customer_stores`
SET `shop_id`                 = :shopId,
    `staff_member_id`         = :staffMemberId,
    `last_order_date`         = :lastOrderDate,
    `number_of_orders`        = :numberOfOrders,
    `number_of_order_cancels` = :numberOrderCancels,
    `remaining_point`         = :remainingPoint,
    `customer_note`           = :customerNote,
    `update_date`             = NOW()
WHERE `id` = :id;
