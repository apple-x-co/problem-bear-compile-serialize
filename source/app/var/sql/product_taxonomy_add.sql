/* product_taxonomy_add */
INSERT INTO `product_taxonomies` (`id`, `product_id`, `taxonomy_id`, `position`, `create_date`, `update_date`)
VALUES (0, :productId, :taxonomyId, :position, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
