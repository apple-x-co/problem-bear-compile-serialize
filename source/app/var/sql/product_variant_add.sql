/* product_variant_add */
INSERT INTO `product_variants` (`id`, `product_id`, `title`, `code`, `sku`, `original_price`, `price`, `discount_price`,
                                `pickup_duration_days`, `create_date`, `update_date`)
VALUES (0, :productId, :title, :code, :sku, :originalPrice, :price, :discountPrice, :pickupDurationDays, NOW(),
        NOW())

SELECT LAST_INSERT_ID() AS id;
