/* customer_store_item_by_unique_key */
SELECT `id`,
       `customer_id`,
       `store_id`,
       `shop_id`,
       `staff_member_id`,
       `last_order_date`,
       `number_of_orders`,
       `number_of_order_cancels`,
       `remaining_point`,
       `customer_note`
FROM `customer_stores`
WHERE `customer_id` = :customerId
  AND `store_id` = :storeId;
