/* product_property_update */
UPDATE `product_properties`
SET `value`       = :value,
    `update_date` = NOW()
WHERE `product_id` = :productId
  AND `name` = : name;

SELECT ROW_COUNT() AS row_count;
