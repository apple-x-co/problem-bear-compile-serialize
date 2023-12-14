/** seller_topic_list_by_published */
SELECT `id`, `publish_start_date`, `publish_end_date`, `title`, `text`
FROM `seller_topics`
WHERE `publish_start_date` <= NOW()
  AND (`publish_end_date` IS NULL OR `publish_end_date` >= NOW())
ORDER BY `publish_start_date` DESC, `publish_end_date` DESC;
