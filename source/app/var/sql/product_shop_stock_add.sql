/* product_shop_stock_add */
INSERT INTO `product_shop_stocks` (`id` `product_id`, `shop_id`, `status`, `create_date`, `update_date`)
VALUES (0, :productId, :shopId, :status, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
