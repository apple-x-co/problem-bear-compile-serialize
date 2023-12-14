/* staff_member_add */
INSERT INTO `staff_members` (`id`, `company_id`, `shop_id`, `name`, `code`, `email`, `password`, `message`, `position`,
                             `status`, `create_date`, `update_date`)
VALUES (0, :company_id, :shop_id, :name, :code, :email, :password, :message, :position, :status, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
