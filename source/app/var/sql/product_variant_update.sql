/* product_variant_update */
UPDATE `product_variants`
SET `title`                = :title,
    `code`                 = :code,
    `sku`                  = :sku,
    `original_price`       = :originalPrice,
    `price`                = :price,
    `discount_price`       = :discountPrice,
    `pickup_duration_days` = :pickupDurationDays,
    `update_date`          = NOW()
WHERE `id` = :id;
