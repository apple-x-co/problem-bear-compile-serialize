/* customer_order_list_by_store_customer */
SELECT `id`, `customer_id`, `store_id`, `order_id`
FROM `customer_orders`
WHERE `customer_id` = :customerId
  AND `store_id` = :storeId
ORDER BY `create_date`;
