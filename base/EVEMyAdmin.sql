-- MySQL Script generated by MySQL Workbench
-- 07/07/15 10:40:46
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema EVEMyAdmin
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema EVEMyAdmin
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `EVEMyAdmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `EVEMyAdmin` ;

-- -----------------------------------------------------
-- Table `EVEMyAdmin`.`AUTH_USERS`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `EVEMyAdmin`.`AUTH_USERS` (
  `AU_ID` VARCHAR(25) NOT NULL COMMENT '',
  `AU_EMAIL` VARCHAR(128) NOT NULL COMMENT '',
  `AU_SALT` VARCHAR(128) NOT NULL COMMENT '',
  `AU_PASSWORD` VARCHAR(128) NOT NULL COMMENT '',
  `AU_DATE_LAST` INT(11) NOT NULL COMMENT '',
  `AU_DATE_CREATION` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`AU_ID`)  COMMENT '',
  UNIQUE INDEX `AU_EMAIL_UNIQUE` (`AU_EMAIL` ASC)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `EVEMyAdmin`.`API_KEYS`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `EVEMyAdmin`.`API_KEYS` (
  `AK_ID` VARCHAR(128) NOT NULL COMMENT '',
  `AK_U_ID` VARCHAR(25) NOT NULL COMMENT '',
  `AK_NOM` VARCHAR(32) NOT NULL COMMENT '',
  `AK_VCODE` VARCHAR(128) NOT NULL COMMENT '',
  PRIMARY KEY (`AK_ID`)  COMMENT '',
  INDEX `FK_AU_ID_idx` (`AK_U_ID` ASC)  COMMENT '',
  CONSTRAINT `FK_AU_ID`
    FOREIGN KEY (`AK_U_ID`)
    REFERENCES `EVEMyAdmin`.`AUTH_USERS` (`AU_ID`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
