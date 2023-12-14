/* company_item */
SELECT `id`,
       `name`,
       `seller_slug`,
       `seller_url`,
       `consumer_slug`,
       `consumer_url`,
       `store_id`,
       `payment_method_id`,
       `status`,
       `leave_date`,
       `void_date`
FROM `companies`
WHERE `id` = :id;
