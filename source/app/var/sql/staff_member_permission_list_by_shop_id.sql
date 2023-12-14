/* staff_member_permission_list_by_shop_id */
SELECT `id`, `staff_member_id`, `access`, `resource_name`, `permission`
FROM `staff_member_permissions`
WHERE EXISTS (SELECT 1
              FROM `staff_members`
              WHERE `staff_members`.`id` = `staff_member_permissions`.`staff_member_id`
                AND `staff_members`.`shop_id` = :shopId)
ORDER BY `staff_member_id`, `access`, `resource_name`;

