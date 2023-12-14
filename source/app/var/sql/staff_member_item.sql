/* staff_member_item */
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
WHERE `id` = :id;
