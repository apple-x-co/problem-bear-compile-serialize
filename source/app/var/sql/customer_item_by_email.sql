/* customer_item_by_email */
SELECT `id`,
       `family_name`,
       `given_name`,
       `phonetic_family_name`,
       `phonetic_given_name`,
       `gender_type`,
       `phone_number`,
       `email`,
       `password`,
       `joined_date`,
       `status`
FROM `customers`
WHERE `email` = :email;
