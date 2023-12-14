/* purpose_item */
SELECT `id`, `store_id`, `parent_id`, `name`, `position`
FROM `purposes`
WHERE `id` = :id;
