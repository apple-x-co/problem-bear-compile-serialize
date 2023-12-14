/* product_shop_stock_list_by_product_id */
SELECT `id` `product_id`, `shop_id`, `status`
FROM `product_shop_stocks`
WHERE `product_id` = :productId;
