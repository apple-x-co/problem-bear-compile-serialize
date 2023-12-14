/* product_purpose_delete_old */
DELETE
FROM `product_purposes`
WHERE `product_id` = :productId
  AND `id` NOT IN (:aliveIds);
