/* product_maker_add */
INSERT INTO `product_makers` (`id`, `product_id`, `maker_id`, `position`, `create_date`, `update_date`)
VALUES (0, :productId, :makerId, :position, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
