/* discount_code_update */
UPDATE discount_codes
SET `title`             = :title,
    `type`              = :type,
    `value`             = :value,
    `start_date`        = :startDate,
    `end_date`          = :endDate,
    `usage_count`       = :usageCount,
    `usage_limit`       = :usageLimit,
    `minimum_price`     = :minimumPrice,
    `once_per_customer` = :oncePerCustomer,
    `target_selection`  = :targetSelection,
    `status`            = :status,
    `update_date`       = NOW()
WHERE `id` = :id;
