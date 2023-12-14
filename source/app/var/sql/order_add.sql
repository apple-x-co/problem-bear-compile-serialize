/* order_add */
INSERT INTO `orders` (`id`, `store_id`, `order_no`, `order_date`, `close_date`, `family_name`, `given_name`,
                      `phonetic_family_name`, `phonetic_given_name`, `postal_code`, `state`, `city`, `address_line_1`,
                      `address_line_2`, `phone_number`, `email`, `discount_code`, `discount_price`, `point_rate`,
                      `spending_point`, `earning_point`, `total_price`, `total_tax`, `subtotal_price`,
                      `payment_method_id`, `payment_method_name`, `payment_fee`, `note`, `order_note`, `pickup_status`,
                      `status`, `create_date`, `update_date`)
VALUES (0, :storeId, :orderNo, :orderDate, :closeDate, :familyName, :givenName, :phoneticFamilyName, :phoneticGivenName,
        :postalCode, :state, :city, :addressLine1, :addressLine2, :phoneNumber, :email, :discountCode, :discountPrice,
        :pointRate, :spendingPoint, :earningPoint, :totalPrice, :totalTax, :subtotalPrice, :paymentMethodId,
        :paymentMethodName, :paymentFee, :note, :orderNote, :pickupStatus, :status, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
