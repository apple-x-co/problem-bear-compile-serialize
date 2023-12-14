/* refund_update */
UPDATE `refunds`
SET `status` = :status
WHERE `id` = :id;
