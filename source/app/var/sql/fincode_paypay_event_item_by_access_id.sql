/* fincode_paypay_event_item_by_order_id */
SELECT `id`,
       `shop_id`,
       `access_id`,
       `order_id`,
       `amount`,
       `tax`,
       `customer_id`,
       `process_date`,
       `code_expiry_date`,
       `auth_max_date`,
       `code_id`,
       `payment_id`,
       `payment_date`,
       `error_code`,
       `pay_type`,
       `event`,
       `job_cd`,
       `status`
FROM `fincode_paypay_events`
WHERE `access_id` = :accessId;
