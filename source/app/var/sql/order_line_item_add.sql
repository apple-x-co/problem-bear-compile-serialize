/* order_line_item_add */
INSERT INTO `order_line_items` (`id`, `order_id`, `product_id`, `product_variant_id`, `title`, `maker_name`,
                                `taxonomy_name`, `discount_price`, `original_price`, `original_tax`,
                                `original_line_price`, `final_price`, `final_tax`, `final_line_price`, `tax_rate`,
                                `quantity`, `create_date`, `update_date`)
VALUES (0, :orderId, :productId, :productVariantId, :title, :makerName, :taxonomyName, :discountPrice, :originalPrice,
        :originalTax, :originalLinePrice, :finalPrice, :finalTax, :finalLinePrice, :taxRate, :quantity, NOW(), NOW())

SELECT LAST_INSERT_ID() AS id;
