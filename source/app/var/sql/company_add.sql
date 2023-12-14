/* company_add */
INSERT INTO `companies` (`id`, `name`, `seller_slug`, `seller_url`, `consumer_slug`, `consumer_url`, `store_id`,
                         `payment_method_id`, `status`, `leave_date`, `void_date`, `create_date`, `update_date`)
VALUES (0, :name, :sellerSlug, :sellerUrl, :consumerSlug, :consumerUrl, :storeId, :paymentMethodId, :status,
        :leaveDate, :voidDate, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
