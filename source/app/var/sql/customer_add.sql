/* customer_add */
INSERT INTO `customers` (`id`, `family_name`, `given_name`, `phonetic_family_name`, `phonetic_given_name`, `gender_type`, `phone_number`, `email`, `password`, `joined_date`, `status`, `create_date`, `update_date`)
VALUES (0, :familyName, :givenName, :phoneticFamilyName, :phoneticGivenName, :genderType, :phoneNumber, :email, :password, :joinedDate, :status, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
