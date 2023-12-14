/* stock_add */
INSERT INTO `stocks` (`id`, `store_id`, `product_variant_id`, `idempotency_token`, `quantity`, `create_date`,
                      `update_date`)
VALUES (0, :store_id, :product_variant_id, :idempotency_token, :quantity, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
