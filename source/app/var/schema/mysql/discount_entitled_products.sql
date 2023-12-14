DROP TABLE IF EXISTS `discount_entitled_products`;

CREATE TABLE `discount_entitled_products`
(
    `id`               INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `discount_code_id` INTEGER UNSIGNED                NOT NULL COMMENT '割引コードID',
    `product_id`       INTEGER UNSIGNED                NOT NULL COMMENT '商品ID',
    `create_date`      DATETIME                        NOT NULL,
    `update_date`      DATETIME                        NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_discount_entitled_products_1` FOREIGN KEY (`discount_code_id`) REFERENCES `discount_codes` (`id`),
    CONSTRAINT `fk_discount_entitled_products_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = '割引対象商品';
