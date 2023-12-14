/* product_purpose_add */
INSERT INTO `product_purposes` (`id`, `product_id`, `purpose_id`, `position`, `create_date`, `update_date`)
VALUES (0, :productId, :purposeId, :position, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
