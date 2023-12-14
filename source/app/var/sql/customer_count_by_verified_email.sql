/* customer_count_by_verified_email */
SELECT COUNT(`id`) AS count
FROM `customers`
WHERE `email` = :email
AND `status` = 'verified';
