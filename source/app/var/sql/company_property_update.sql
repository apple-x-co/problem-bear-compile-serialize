/* company_property_update */
UPDATE `company_properties`
SET `value`       = :value,
    `update_date` = NOW()
WHERE `company_id` = :companyId
  AND `name` = :name;

SELECT ROW_COUNT() AS row_count;
