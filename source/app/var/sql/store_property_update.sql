/* store_property_update */
UPDATE `store_properties`
SET `value`       = :value,
    `update_date` = NOW()
WHERE `store_id` = :storeId
  AND `name` = : name;

SELECT ROW_COUNT() AS row_count;
