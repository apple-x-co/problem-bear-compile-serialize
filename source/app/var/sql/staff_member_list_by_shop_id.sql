/* staff_member_list_by_shop_id */
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
WHERE `shop_id` = :shopId;
