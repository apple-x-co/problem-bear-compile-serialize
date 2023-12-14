/* company_property_add */
INSERT INTO `company_properties` (`id`, `company_id`, `name`, `value`, `create_date`, `update_date`)
VALUES (0, :companyId, :name, :value, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
