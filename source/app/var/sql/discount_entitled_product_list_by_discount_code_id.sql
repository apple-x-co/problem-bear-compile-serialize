/* discount_entitled_product_list_by_discount_code_id */
SELECT `id`, `discount_code_id`, `product_id`
FROM `discount_entitled_products`
WHERE `discount_code_id` = :discountCodeId;
