/* shop_add */
INSERT INTO `shops` (`id`, `company_id`, `area_id`, `name`, `position`, `create_date`, `update_date`)
VALUES (0, :companyId, :areaId, :name, :posiition, NOW(), NOW())

SELECT LAST_INSERT_ID() AS id;
