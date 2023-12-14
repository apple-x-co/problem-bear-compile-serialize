/* maker_item */
SELECT `id`, `store_id`, `parent_id`, `name`, `position`
FROM `maker`
WHERE `id` = :id;
