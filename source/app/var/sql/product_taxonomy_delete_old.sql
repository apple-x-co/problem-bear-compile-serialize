/* product_taxonomy_delete_old */
DELETE
FROM `product_taxonomies`
WHERE `product_id` = :productId
  AND `id` NOT IN (:aliveIds);
