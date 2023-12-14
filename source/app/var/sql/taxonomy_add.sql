/* taxonomy_add */
INSERT INTO `taxonomies` (`id`, `store_id`, `parent_id`, `name`, `position`, `create_date`, `update_date`)
VALUES (0, :storeId, :parentId, :name, :position, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
