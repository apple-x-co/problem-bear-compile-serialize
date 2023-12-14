/* order_update */
UPDATE `orders`
SET `close_date`    = :closeDate,
    `note`          = :note,
    `order_note`    = :orderNote,
    `pickup_status` = :pickupStatus,
    `status`        = :status,
    `update_date`   = NOW()
WHERE `id` = :id;
