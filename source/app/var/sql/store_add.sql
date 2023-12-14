/* store_add */
INSERT INTO `stores` (`id`, `url`, `key`, `name`, `status`, `leave_date`, `void_date`, `create_date`, `update_date`)
VALUES (0, :url, :key, :name, :status, :leaveDate, :voidDate, NOW(), NOW())

SELECT LAST_INSERT_ID() AS id;
