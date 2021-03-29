-- MySQL Script generated by MySQL Workbench
-- Mon Mar 29 15:30:28 2021
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema laureldb
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `laureldb` ;

-- -----------------------------------------------------
-- Schema laureldb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `laureldb` DEFAULT CHARACTER SET utf8 COLLATE utf8_hungarian_ci ;
USE `laureldb` ;

-- -----------------------------------------------------
-- Table `laureldb`.`page`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `laureldb`.`page` ;

CREATE TABLE IF NOT EXISTS `laureldb`.`page` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL COMMENT 'Page Name',
  `stylesheet` VARCHAR(50) NULL DEFAULT NULL COMMENT 'Used Stylesheet',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_hungarian_ci
COMMENT = 'Available pages to show';

CREATE UNIQUE INDEX `id_UNIQUE` ON `laureldb`.`page` (`id` ASC) VISIBLE;

CREATE UNIQUE INDEX `name_UNIQUE` ON `laureldb`.`page` (`name` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `laureldb`.`role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `laureldb`.`role` ;

CREATE TABLE IF NOT EXISTS `laureldb`.`role` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL COMMENT 'Role Name',
  `description` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Role Description',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_hungarian_ci;

CREATE UNIQUE INDEX `id_UNIQUE` ON `laureldb`.`role` (`id` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `laureldb`.`role_has_page`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `laureldb`.`role_has_page` ;

CREATE TABLE IF NOT EXISTS `laureldb`.`role_has_page` (
  `role_id` INT(10) UNSIGNED NOT NULL,
  `page_id` INT(11) NOT NULL,
  PRIMARY KEY (`role_id`, `page_id`),
  CONSTRAINT `fk_role_has_page_role1`
    FOREIGN KEY (`role_id`)
    REFERENCES `laureldb`.`role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_role_has_page_page1`
    FOREIGN KEY (`page_id`)
    REFERENCES `laureldb`.`page` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_hungarian_ci;

CREATE INDEX `fk_role_has_page_page1_idx` ON `laureldb`.`role_has_page` (`page_id` ASC) VISIBLE;

CREATE INDEX `fk_role_has_page_role1_idx` ON `laureldb`.`role_has_page` (`role_id` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `laureldb`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `laureldb`.`user` ;

CREATE TABLE IF NOT EXISTS `laureldb`.`user` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(16) NOT NULL COMMENT 'User Name',
  `email` VARCHAR(255) NOT NULL COMMENT 'User Email',
  `password` VARCHAR(60) NOT NULL COMMENT 'Password',
  `create_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` TIMESTAMP NULL DEFAULT NULL,
  `logged_in` TINYINT(4) NULL DEFAULT '0',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_hungarian_ci;

CREATE UNIQUE INDEX `id_UNIQUE` ON `laureldb`.`user` (`id` ASC) VISIBLE;

CREATE INDEX `idx_email` ON `laureldb`.`user` (`email` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `laureldb`.`user_has_role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `laureldb`.`user_has_role` ;

CREATE TABLE IF NOT EXISTS `laureldb`.`user_has_role` (
  `user_id` INT(10) UNSIGNED NOT NULL,
  `role_id` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`, `role_id`),
  CONSTRAINT `fk_user_has_role_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `laureldb`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_role_role1`
    FOREIGN KEY (`role_id`)
    REFERENCES `laureldb`.`role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_hungarian_ci;

CREATE INDEX `fk_user_has_role_role1_idx` ON `laureldb`.`user_has_role` (`role_id` ASC) VISIBLE;

CREATE INDEX `fk_user_has_role_user_idx` ON `laureldb`.`user_has_role` (`user_id` ASC) VISIBLE;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
