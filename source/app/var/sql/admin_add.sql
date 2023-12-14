/* admin_add */
INSERT INTO `admins` (`id`, `name`, `username`, `password`, `create_date`, `update_date`)
VALUES (0, :name, :username, :password, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
