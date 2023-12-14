/* staff_member_permission_delete_old */
DELETE
FROM `staff_member_permissions`
WHERE `staff_member_id` = :staffMemberId
  AND `id` NOT IN (:aliveIds);
