/* product_maker_item */
SELECT `id`, `product_id`, `maker_id`, `position`
FROM `product_makers`
WHERE `id` = :id;
