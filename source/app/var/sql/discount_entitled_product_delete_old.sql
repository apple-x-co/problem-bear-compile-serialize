/* discount_entitled_product_delete_old */
DELETE
FROM `discount_entitled_products`
WHERE `discount_code_id` = :discountCodeId
  AND `id` NOT IN (:aliveIds);
