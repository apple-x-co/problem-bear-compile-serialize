/* store_image_update */
UPDATE `store_images`
SET `position`    = :position,
    `update_date` = NOW()
WHERE `id` = :id;
