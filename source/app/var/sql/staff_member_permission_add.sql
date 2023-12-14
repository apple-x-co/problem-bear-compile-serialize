/* staff_member_permission_add */
INSERT INTO `staff_member_permissions` (`id`, `staff_member_id`, `access`, `resource_name`, `permission`, `create_date`,
                                        `update_date`)
VALUES (0, :staffMemberId, :access, :resourceName, :permission, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
