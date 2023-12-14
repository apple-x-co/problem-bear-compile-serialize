/* store_usage_billing_list_by_store_id */
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
WHERE EXISTS (SELECT 1
              FROM `store_usages`
              WHERE `store_usages`.`id` = `store_usage_billings`.`store_usage_id`
                AND `store_usages`.`store_id` = :storeId);
