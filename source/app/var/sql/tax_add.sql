/* tax_add */
INSERT INTO `taxes` (`id`, `name`, `rate`, `create_date`, `update_date`)
VALUES (0, :name, :rate, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
