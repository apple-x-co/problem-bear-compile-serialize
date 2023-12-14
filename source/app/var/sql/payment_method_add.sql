/* payment_method_add */
INSERT INTO `payment_methods` (`id`, `name`, `key`, `role`, `fee`, `available`, `position`)
VALUES (0, :name, :key, :role, :fee, :available, :position);

SELECT LAST_INSERT_ID() AS id;
