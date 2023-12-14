/* product_maker_delete_old */
DELETE
FROM `product_makers`
WHERE `product_id` = :productId
  AND `id` NOT IN (:aliveIds);
