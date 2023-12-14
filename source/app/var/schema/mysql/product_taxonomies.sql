DROP TABLE IF EXISTS `product_taxonomies`;

CREATE TABLE `product_taxonomies`
(
    `id`          INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `product_id`  INTEGER UNSIGNED                NOT NULL COMMENT '商品ID',
    `taxonomy_id` INTEGER UNSIGNED                NOT NULL COMMENT '分類ID',
    `position`    SMALLINT UNSIGNED               NOT NULL COMMENT '並び順',
    `create_date` DATETIME                        NOT NULL,
    `update_date` DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_product_taxonomies_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
    CONSTRAINT `fk_product_taxonomies_2` FOREIGN KEY (`taxonomy_id`) REFERENCES `taxonomies` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '商品分類';
