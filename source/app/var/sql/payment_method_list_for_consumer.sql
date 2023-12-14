/* payment_method_list_for_consumer */
SELECT `id`, `name`, `key`, `role`, `fee`, `available`, `position`
FROM `payment_methods`
WHERE `role` = 'customer'
ORDER BY `position`;
