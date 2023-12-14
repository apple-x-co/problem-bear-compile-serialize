/* discount_code_activity_add */
INSERT INTO `discount_code_activities` (`id`, `store_id`, `code`, `customer_id`, `email`, `phone_number`, `used_date`,
                                        `create_date`, `update_date`)
VALUES (0, :storeId, :code, :customerId, :email, :phoneNumber, :usedDate, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
