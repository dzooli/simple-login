DROP DATABASE IF EXISTS `laureldb`;
CREATE DATABASE `laureldb` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_hungarian_ci */;

CREATE USER `laureluser`@`%` IDENTIFIED BY 'laurelpass';
GRANT DELETE,INSERT,SELECT,UPDATE,CREATE VIEW,CREATE TEMPORARY TABLES ON `laureldb`.* TO `laureluser`@`%`;
FLUSH PRIVILEGES;

CREATE TABLE `laureldb`.`user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(16) NOT NULL COMMENT 'User Name',
  `email` VARCHAR(255) NOT NULL COMMENT 'User Email',
  `password` VARCHAR(60) NOT NULL COMMENT 'Password',
  `create_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` TIMESTAMP NULL,
  `logged_in` TINYINT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `idx_email` (`email` ASC));

INSERT INTO `laureldb`.`user` (`username`, `email`, `password`) VALUES ('admin', 'admin@email.com', '$2y$10$JU8h7esuD5rvqvBsXbrdz.yp4iq3PnbqsJTkGbap3yb6K.cYVT3Om');
INSERT INTO `laureldb`.`user` (`username`, `email`, `password`) VALUES ('user', 'user@email.com', '$2y$10$vzMIpJsB8MQxYYCi.Y1VX.G4xPH8ZO79updhNuw.c7e9pxu2QsCRi');

CREATE TABLE `laureldb`.`role` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL COMMENT 'Role Name',
  `description` VARCHAR(255) NULL COMMENT 'Role Description',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC));

INSERT INTO `laureldb`.`role` (`name`, `description`) VALUES ('admin', 'Administrator');
INSERT INTO `laureldb`.`role` (`name`, `description`) VALUES ('user', 'Normal user');
INSERT INTO `laureldb`.`role` (`name`, `description`) VALUES ('operator', 'Operational user');

CREATE TABLE `laureldb`.`page` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL COMMENT 'Page Name',
  `stylesheet` VARCHAR(50) NULL COMMENT 'Used Stylesheet',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
COMMENT = 'Available pages to show';

