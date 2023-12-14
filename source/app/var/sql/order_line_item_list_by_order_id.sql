/* order_line_item_list_by_order_id */
SELECT `id`,
       `order_id`,
       `product_id`,
       `product_variant_id`,
       `title`,
       `maker_name`,
       `taxonomy_name`,
       `discount_price`,
       `original_price`,
       `original_tax`,
       `original_line_price`,
       `final_price`,
       `final_tax`,
       `final_line_price`,
       `tax_rate`,
       `quantity`
FROM `order_line_items`
WHERE `order_id` = :orderId;
