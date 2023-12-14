/* featured_product_add */
INSERT INTO `featured_products` (`id`, `store_id`, `product_id`, `position`, `create_date`, `update_date`)
VALUES (0, :storeId, :productId, :position, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
