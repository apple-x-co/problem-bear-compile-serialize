/* customer_favorite_product_item_by_unique_key */
SELECT `id`, `customer_id`, `store_id`, `product_id`, `favorited_date`
FROM `customer_favorite_products`
WHERE `customer_id` = :customerId
  AND `product_id` = :productId;
