/* product_purpose_item */
SELECT `id`, `product_id`, `purpose_id`, `position`
FROM `product_purposes`
WHERE `id` = :id;
