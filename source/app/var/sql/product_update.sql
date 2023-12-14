/* product_update */
UPDATE `products`
SET `title`                         = :title,
    `code`                          = :code,
    `taxable`                       = :taxable,
    `tax_id`                        = :taxId,
    `original_price`                = :originalPrice,
    `price`                         = :price,
    `number_of_pieces`              = :numberOfPieces,
    `discount_rate`                 = :discountRate,
    `discount_price`                = :discountPrice,
    `stockist_notification_enabled` = :stockistNotificationEnabled,
    `stockist_name`                 = :stockistName,
    `sale_start_date`               = :saleStartDate,
    `sale_end_date`                 = :saleEndDate,
    `status`                        = :status,
    `update_date`                   = NOW()
WHERE `id` = :id;
