/* store_usage_billing_add */
INSERT INTO `store_usage_billings` (`id`, `store_usage_id`, `charge_amount`, `billing_date`, `scheduled_payment_date`,
                                    `due_date`, `paid_date`, `family_name`, `given_name`, `phonetic_family_name`,
                                    `phonetic_given_name`, `postal_code`, `state`, `city`, `address_line_1`,
                                    `address_line_2`, `phone_number`, `email`, `status`, `create_date`, `update_date`)
VALUES (0, :storeUsageId, :chargeAmount, :billingDate, :scheduledPaymentDate, :dueDate, :paidDate, :familyName,
        :givenName, :phoneticFamilyName, :phoneticGivenName, :postalCode, :state, :city, :addressLine1, :addressLine2,
        :phoneNumber, :email, :status, NOW(), NOW())

SELECT LAST_INSERT_ID() AS id;
