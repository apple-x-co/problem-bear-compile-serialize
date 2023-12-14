/* discount_code_add */
INSERT INTO `discount_codes` (`id`, `store_id`, `title`, `code`, `type`, `value`, `start_date`, `end_date`,
                              `usage_count`, `usage_limit`, `minimum_price`, `once_per_customer`, `target_selection`,
                              `status`, `create_date`, `update_date`)
VALUES (0, :storeId, :title, :code, :type, :value, :startDate, :endDate, :usageCount, :usageLimit, :minimumPrice,
        :oncePerCustomer, :targetSelection, :status, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
