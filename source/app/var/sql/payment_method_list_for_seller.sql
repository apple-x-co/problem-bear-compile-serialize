/* payment_method_list_for_seller */
SELECT `id`, `name`, `key`, `role`, `fee`, `available`, `position`
FROM `payment_methods`
WHERE `role` = 'admin'
ORDER BY `position`;
