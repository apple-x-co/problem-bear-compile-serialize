/* featured_product_list_by_store_id */
SELECT `id`, `store_id`, `product_id`, `position`
FROM `featured_products`
WHERE `store_id` = :storeId
ORDER BY `position`;
