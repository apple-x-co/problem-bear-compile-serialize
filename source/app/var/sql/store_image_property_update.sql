/* store_image_property_update */
UPDATE `store_image_properties`
SET `value`       = :value,
    `update_date` = NOW()
WHERE `store_image_id` = :storeImageId
  AND `name` = : name;

SELECT ROW_COUNT() AS row_count;
