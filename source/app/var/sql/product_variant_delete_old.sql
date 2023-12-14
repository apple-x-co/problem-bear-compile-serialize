/* product_variant_delete_old */
DELETE
FROM `product_variants`
WHERE `product_id` = :productId
  AND `id` NOT IN (:aliveIds);
