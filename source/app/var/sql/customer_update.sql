/* customer_update */
UPDATE `customers`
SET `family_name`          = :familyName,
    `given_name`           = :givenName,
    `phonetic_family_name` = :phoneticFamilyName,
    `phonetic_given_name`  = :phoneticGivenName,
    `gender_type`          = :genderType,
    `phone_number`         = :phoneNumber,
    `email`                = :email,
    `password`             = :password,
    `joined_date`          = :joinedDate,
    `status`               = :status,
    `update_date`          = NOW()
WHERE `id` = :id;
