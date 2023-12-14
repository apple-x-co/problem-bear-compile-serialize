/* discount_code_item_by_unique_key */
SELECT `id`,
       `store_id`,
       `title`,
       `code`,
       `type`,
       `value`,
       `start_date`,
       `end_date`,
       `usage_count`,
       `usage_limit`,
       `minimum_price`,
       `once_per_customer`,
       `target_selection`,
       `status`
FROM `discount_codes`
WHERE `store_id` = :storeId
  AND `code` = :code;
