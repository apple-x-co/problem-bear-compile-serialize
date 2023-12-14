/* customer_favorite_product_add */
INSERT INTO `customer_favorite_products` (`id`, `customer_id`, `store_id`, `product_id`, `favorited_date`, `create_date`, `update_date`)
VALUES (0, :customerId, :storeId, :productId, :favoritedDate, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
