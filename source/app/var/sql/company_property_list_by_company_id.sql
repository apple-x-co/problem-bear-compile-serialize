/* company_property_list_by_company_id */
SELECT `id`, `company_id`, `name`, `value`
FROM `company_properties`
WHERE `company_id` = :companyId;
