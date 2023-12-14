/* order_pickup_add */
INSERT INTO `order_pickups` (`id`, `order_id`, `pickup_date`, `pickup_time`, `shop_id`, `shop_name`, `staff_member_id`,
                             `staff_member_name`, `create_date`, `update_date`)
VALUES (0, :orderId, :pickupDate, :pickupTime, :shopId, :shopName, :staffMemberId, :staffMemberName, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
