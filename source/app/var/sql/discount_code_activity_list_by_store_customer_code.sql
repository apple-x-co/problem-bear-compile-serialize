/* discount_code_activity_list_by_store_customer_code */
SELECT `id`,
       `store_id`,
       `code`,
       `customer_id`,
       `email`,
       `phone_number`,
       `used_date`
FROM `discount_code_activities`
WHERE (`store_id` = :storeId AND `code` = :code,
       `customer_id` IS NULL AND (`email` = :email OR `phone_number` = :phoneNumber))
   OR (`store_id` = :storeId AND `code` = :code, `customer_id` = :customerId)
