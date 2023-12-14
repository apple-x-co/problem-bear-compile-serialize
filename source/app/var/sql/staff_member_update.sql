/* staff_member_update */
UPDATE `staff_members`
SET `shop_id`     = :shop_id,
    `name`        = :name,
    `code`        = :code,
    `email`       = :email,
    `password`    = :password,
    `message`     = :message,
    `position`    = :position,
    `status`      = :stauts,
    `update_date` = NOW()
WHERE `id` = :id;
