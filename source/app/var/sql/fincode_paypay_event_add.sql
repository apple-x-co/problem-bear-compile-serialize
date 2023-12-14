/* fincode_paypay_event_add */
INSERT INTO `fincode_paypay_events` (`id`, `shop_id`, `access_id`, `order_id`, `amount`, `tax`, `customer_id`,
                                     `process_date`, `code_expiry_date`, `auth_max_date`, `code_id`, `payment_id`,
                                     `payment_date`, `error_code`, `pay_type`, `event`, `job_cd`, `status`,
                                     `create_date`, `update_date`)
VALUES (0, :shopId, :accessId, :orderId, :amount, :tax, :customerId, :processDate, :codeExpiryDate, :authMaxDate,
        :codeId, :paymentId, :paymentDate, :errorCode, :payType, :event, :jobCd, :status, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
