/* customer_favorite_product_update */
DELETE
FROM `customer_favorite_products`
WHERE `id` = :id
  AND `customer_id` = :customerId
  AND `product_id` = :productId;
