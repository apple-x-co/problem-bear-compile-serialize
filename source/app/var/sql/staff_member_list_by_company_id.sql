/* staff_member_list_by_company_id */
SELECT `id`,
       `company_id`,
       `shop_id`,
       `name`,
       `code`,
       `email`,
       `password`,
       `message`,
       `position`,
       `status`
FROM `staff_members`
WHERE `company_id` = :companyId;
