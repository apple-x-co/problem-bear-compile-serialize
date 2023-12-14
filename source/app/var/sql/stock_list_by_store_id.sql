/* stock_list_by_store_id */
SELECT `id`, `store_id`, `product_variant_id`, `idempotency_token`, `quantity`
FROM `stocks`
WHERE `store_id` = :storeId;
