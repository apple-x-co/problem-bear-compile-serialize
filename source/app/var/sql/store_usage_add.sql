/* store_usage_add */
INSERT INTO `store_usages` (`id`, `store_id`, `description`, `target_date`, `total_price`, `status`, `create_date`,
                            `update_date`)
VALUES (0, :storeId, :description, :targetDate, :totalPrice, :status, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
