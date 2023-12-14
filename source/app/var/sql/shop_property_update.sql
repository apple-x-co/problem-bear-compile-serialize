/* shop_property_update */
UPDATE `shop_properties`
SET `value`       = :value,
    `update_date` = NOW()
WHERE `shop_id` = :shopId
  AND `name` = :name;

SELECT ROW_COUNT() AS row_count;
