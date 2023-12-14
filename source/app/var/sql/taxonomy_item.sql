/* taxonomy_item */
SELECT `id`, `store_id`, `parent_id`, `name`, `position`
FROM `taxonomies`
WHERE `id` = :id;
