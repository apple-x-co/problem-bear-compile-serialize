/* popular_product_list_by_store_id */
SELECT `id`, `store_id`, `product_id`, `position`
FROM `popular_products`
WHERE `store_id` = :storeId
ORDER BY `position`;
