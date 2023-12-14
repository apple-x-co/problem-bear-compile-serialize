/* staff_member_permission_update */
UPDATE `staff_member_permissions`
SET `access`        = :access,
    `resource_name` = :resource_name,
    `permission`    = :permission,
    `update_date`   = NOW()
WHERE `id` = :id;
