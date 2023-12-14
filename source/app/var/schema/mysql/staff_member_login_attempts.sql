DROP TABLE IF EXISTS `staff_member_login_attempts`;

CREATE TABLE `staff_member_login_attempts`
(
    `id`               BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `email`            VARCHAR(255)                   NOT NULL COMMENT 'Eメールアドレス',
    `number_of_trials` INTEGER UNSIGNED               NOT NULL COMMENT '試行回数',
    `last_exception`   VARCHAR(255)                   NOT NULL COMMENT '最終例外',
    `last_trial_date`  DATETIME                       NOT NULL COMMENT '最終試行日時',
    `prev_trial_date`  DATETIME                       NULL COMMENT '前回試行日時',
    `expire_date`      DATETIME                       NOT NULL COMMENT '有効期限日時',
    `created_date`     DATETIME                       NOT NULL,
    `update_date`      DATETIME                       NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `ix_staff_member_logins_1` (`email`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT = 'スタッフログイン試行';
