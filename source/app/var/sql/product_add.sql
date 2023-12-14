/* product_add */
INSERT INTO `products` (`id`, `store_id`, `title`, `code`, `taxable`, `tax_id`, `original_price`, `price`,
                        `number_of_pieces`, `discount_rate`, `discount_price`, `stockist_notification_enabled`,
                        `stockist_name`, `sale_start_date`, `sale_end_date`, `status`, `create_date`, `update_date`)
VALUES (0, :storeId, :title, :code, :taxable, :taxId, :originalPrice, :price, :numberOfPieces, :discountRate,
        :discountPrice, :stockistNotificationEnabled, :stockistName, :saleStartDate, :saleEndDate, :status, NOW(),
        NOW());

SELECT LAST_INSERT_ID() AS id;
