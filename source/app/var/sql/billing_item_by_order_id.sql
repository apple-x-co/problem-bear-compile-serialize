/* billing_item_by_order_id */
SELECT `id`,
       `order_id`,
       `billing_no`,
       `charge_amount`,
       `billing_date`,
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
       `email`
FROM `billings`
WHERE `order_id` = :orderId;
