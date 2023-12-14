/* company_update */
UPDATE `companies`
SET `name`              = :name,
    `seller_slug`       = :sellerSlug,
    `seller_url`        = :sellerUrl,
    `consumer_slug`     = :consumerSlug,
    `consumer_url`      = :consumerUrl,
    `store_id`          = :storeId,
    `payment_method_id` = :paymentMethodId,
    `status`            = :status,
    `leave_date`        = :leaveDate,
    `void_date`         = :voidDate,
    `update_date`       = NOW()
WHERE `id` = :id;
