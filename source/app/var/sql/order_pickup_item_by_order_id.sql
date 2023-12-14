/* order_pickup_item_by_order_id */
SELECT `id`,
       `order_id`,
       `pickup_date`,
       `pickup_time`,
       `shop_id`,
       `shop_name`,
       `staff_member_id`,
       `staff_member_name`
FROM `order_pickups`
WHERE `order_id` = :orderId;
