/* staff_member_permission_list_by_staff_member_id */
SELECT `id`, `staff_member_id`, `access`, `resource_name`, `permission`
FROM `staff_member_permissions`
WHERE `staff_member_id` = :staffMemberId
ORDER BY `access`, `resource_name`;

