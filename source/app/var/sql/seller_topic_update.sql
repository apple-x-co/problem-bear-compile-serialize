/** seller_topic_update */
UPDATE `seller_topics`
SET `publish_start_date` = :publish_start_date,
    `publish_end_date`   = :publish_end_date,
    `title`              = :title,
    `text`               = :text,
    `update_date`        = NOW()
WHERE `id` = :id;
