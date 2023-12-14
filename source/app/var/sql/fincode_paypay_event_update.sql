/* fincode_paypay_event_update */
UPDATE `fincode_paypay_events`
SET `amount`           = :amount,
    `tax`              = :tax,
    `process_date`     = :processDate,
    `code_expiry_date` = :codeExpiryDate,
    `auth_max_date`    = :authMaxDate,
    `code_id`          = :codeId,
    `payment_id`       = :paymentId,
    `payment_date`     = :paymentDate,
    `error_code`       = :errorCode,
    `pay_type`         = :payType,
    `event`            = :event,
    `job_cd`           = :jobCd,
    `status`           = :status,
    `update_date`      = NOW()
WHERE `access_id` = :accessId;
