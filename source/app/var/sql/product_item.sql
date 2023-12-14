/* product_item */
SELECT `id`,
       `store_id`,
       `title`,
       `code`,
       `taxable`,
       `tax_id`,
       `original_price`,
       `price`,
       `number_of_pieces`,
       `discount_rate`,
       `discount_price`,
       `stockist_notification_enabled`,
       `stockist_name`,
       `sale_start_date`,
       `sale_end_date`,
       `status`
FROM `products`
WHERE `id` = :id;
