/* store_usage_billing_item_by_store_usage_id */
SELECT `id`,
       `store_usage_id`,
       `charge_amount`,
       `billing_date`,
       `scheduled_payment_date`,
       `due_date`,
       `paid_date`,
       `family_name`,
       `given_name`,
       `phonetic_family_name`,
       `phonetic_given_name`,
       `postal_code`,
       `state`,
       `city`,
       `address_line_1`,
       `address_line_2`,
       `phone_number`,
       `email`,
       `status`
FROM `store_usage_billings`
WHERE `store_usage_id` = :storeUsageId;
