/* billing_add */
INSERT INTO `billings` (`id`, `order_id`, `billing_no`, `charge_amount`, `billing_date`, `family_name`, `given_name`,
                        `phonetic_family_name`, `phonetic_given_name`, `postal_code`, `state`, `city`, `address_line_1`,
                        `address_line_2`, `phone_number`, `email`, `create_date`, `update_date`)
VALUES (0, :orderId, :billingNo, :chargeAmount, :billingDate, :familyName, :givenName, :phoneticFamilyName,
        :phoneticGivenName, :postalCode, :state, :city, :addressLine1, :addressLine2, :phoneNumber, :email, NOW(),
        NOW());

SELECT LAST_INSERT_ID() AS id;
