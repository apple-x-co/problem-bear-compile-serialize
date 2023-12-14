/* discount_entitled_product_add */
INSERT INTO `discount_entitled_products` (`id`, `discount_code_id`, `product_id`, `create_date`, `create_date`)
VALUES (0, :discountCodeId, :productId, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
