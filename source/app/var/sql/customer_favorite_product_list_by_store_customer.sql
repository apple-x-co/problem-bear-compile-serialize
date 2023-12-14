/* customer_favorite_product_list_by_store_customer */
SELECT `id`, `customer_id`, `store_id`, `product_id`, `favorited_date`
FROM `customer_favorite_products`
WHERE `store_id` = :storeId
  AND `customer_id` = :customerId
ORDER BY `favorited_date`;
