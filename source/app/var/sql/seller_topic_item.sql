/** seller_topic_item */
SELECT `id`, `publish_start_date`, `publish_end_date`, `title`, `text`
FROM `seller_topics`
WHERE `id` = :id;
