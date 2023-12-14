/* payment_method_item */
SELECT `id`, `name`, `key`, `role`, `fee`, `available`
FROM `payment_methods`
WHERE `id` = :id;
