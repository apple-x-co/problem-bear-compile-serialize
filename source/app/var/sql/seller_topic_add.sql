/** seller_topic_add */
INSERT INTO `seller_topics` (`id`, `publish_start_date`, `publish_end_date`, `title`, `text`, `create_date`, `update_date`)
VALUES (0, :publishStartDate, :publishEndDate, :title, :text, NOW(), NOW());

SELECT LAST_INSERT_ID() AS id;
