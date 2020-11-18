CREATE TABLE `user` (
    id INT AUTO_INCREMENT NOT NULL,
    name VARCHAR(180) NOT NULL,
    email VARCHAR(180) NOT NULL,
    roles LONGTEXT NOT NULL COMMENT '(DC2Type:json)',
    password VARCHAR(255) NOT NULL,
    UNIQUE INDEX UNIQ_8D93D649E7927C74 (email),
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

INSERT INTO `user` (
    `name`,
    `email`,
    `roles`,
    `password`
) VALUES (
    'Admin',
    'admin@local.host',
    '["ROLE_USER","ROLE_ADMIN"]',
    '$2y$13$zxhpxVzjOCByXoMBdM2rreeFkBCPBhwRrx2RwM.EVgK/HR3Xwuv26'
);
