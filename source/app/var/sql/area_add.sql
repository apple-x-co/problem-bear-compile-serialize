/* area_add */
INSERT INTO `areas` (`id`, `company_id`, `name`, `position`, `create_date`, `update_date`)
VALUES (0, :companyId, :name, :position, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
