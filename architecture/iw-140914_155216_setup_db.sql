SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Table `celestial_body`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestial_body` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `celestial_body` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pos_galaxy` TINYINT UNSIGNED NOT NULL,
  `pos_system` TINYINT UNSIGNED NOT NULL,
  `pos_planet` TINYINT UNSIGNED NOT NULL,
  `density_iron` FLOAT UNSIGNED NOT NULL,
  `density_chemicals` FLOAT UNSIGNED NOT NULL,
  `density_ice` FLOAT UNSIGNED NOT NULL,
  `gravity` FLOAT UNSIGNED NOT NULL,
  `living_conditions` FLOAT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `base`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `base` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `base` (
  `id` INT(11) UNSIGNED NOT NULL,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `stored_iron` INT(11) UNSIGNED NOT NULL,
  `stored_steel` INT(11) UNSIGNED NOT NULL,
  `stored_chemicals` INT(11) UNSIGNED NOT NULL,
  `stored_vv4a` INT(11) UNSIGNED NOT NULL,
  `stored_ice` INT(11) UNSIGNED NOT NULL,
  `stored_water` INT(11) UNSIGNED NOT NULL,
  `stored_energy` INT(11) UNSIGNED NOT NULL,
  `stored_people` INT(11) UNSIGNED NOT NULL,
  `stored_credits` INT(11) UNSIGNED NOT NULL,
  `stored_last_update` DATETIME NOT NULL,
  `produced_steel` INT UNSIGNED NULL DEFAULT NULL COMMENT 'null means \"as much as possible\"',
  `produced_vv4a` INT UNSIGNED NULL DEFAULT NULL COMMENT 'null means \"as much as possible\"',
  `produced_water` INT UNSIGNED NULL DEFAULT NULL COMMENT 'null means \"as much as possible\"',
  PRIMARY KEY (`id`),
  INDEX `fk_base_user1_idx` (`user_id` ASC),
  INDEX `fk_base_celestial_body1_idx` (`id` ASC),
  CONSTRAINT `fk_base_celestial_body1`
    FOREIGN KEY (`id`)
    REFERENCES `celestial_body` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_base_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `user` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `auth_key` VARCHAR(32) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `password_reset_token` VARCHAR(255) NULL DEFAULT NULL,
  `email` VARCHAR(255) NOT NULL,
  `role` SMALLINT(6) NOT NULL DEFAULT '10',
  `status` SMALLINT(6) NOT NULL DEFAULT '10',
  `created_at` INT(11) NOT NULL,
  `updated_at` INT(11) NOT NULL,
  `main_base_id` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_base1_idx` (`main_base_id` ASC),
  UNIQUE INDEX `main_base_id_UNIQUE` (`main_base_id` ASC),
  CONSTRAINT `fk_user_base1`
    FOREIGN KEY (`main_base_id`)
    REFERENCES `base` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `task`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `task` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `task` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(255) NOT NULL,
  `data` TEXT NOT NULL,
  `finished` DATETIME NOT NULL,
  `user_id` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_task_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_task_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `building`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `building` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `building` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `group` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `image` TINYTEXT NOT NULL,
  `description` TEXT NOT NULL,
  `cost_iron` INT(11) UNSIGNED NOT NULL,
  `cost_steel` INT(11) UNSIGNED NOT NULL,
  `cost_chemicals` INT(11) UNSIGNED NOT NULL,
  `cost_vv4a` INT(11) UNSIGNED NOT NULL,
  `cost_ice` INT(11) UNSIGNED NOT NULL,
  `cost_water` INT(11) UNSIGNED NOT NULL,
  `cost_energy` INT(11) UNSIGNED NOT NULL,
  `cost_people` INT(11) UNSIGNED NOT NULL,
  `cost_credits` INT(11) UNSIGNED NOT NULL,
  `cost_time` TIME NOT NULL,
  `balance_iron` INT(11) NOT NULL,
  `balance_steel` INT(11) NOT NULL,
  `balance_chemicals` INT(11) NOT NULL,
  `balance_vv4a` INT(11) NOT NULL,
  `balance_ice` INT(11) NOT NULL,
  `balance_water` INT(11) NOT NULL,
  `balance_energy` INT(11) NOT NULL,
  `balance_people` INT(11) NOT NULL,
  `balance_credits` FLOAT UNSIGNED NOT NULL COMMENT 'Modifier for taxes income.' /* comment truncated */ /*1.5 means tax income increased by 50%.
0.5 means tax income decreased by 50%*/,
  `balance_satisfaction` FLOAT NOT NULL,
  `storage_chemicals` INT UNSIGNED NOT NULL,
  `storage_ice_and_water` INT UNSIGNED NOT NULL,
  `storage_energy` INT UNSIGNED NOT NULL,
  `shelter_iron` INT(11) UNSIGNED NOT NULL,
  `shelter_steel` INT(11) UNSIGNED NOT NULL,
  `shelter_chemicals` INT(11) UNSIGNED NOT NULL,
  `shelter_vv4a` INT(11) UNSIGNED NOT NULL,
  `shelter_ice_and_water` INT(11) UNSIGNED NOT NULL,
  `shelter_energy` INT(11) UNSIGNED NOT NULL,
  `shelter_people` INT(11) UNSIGNED NOT NULL,
  `highscore_points` SMALLINT UNSIGNED NOT NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `index2` (`modified` DESC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Data in this table is unlikely to change. This makes it a perfect candidate for caching.';

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `buildings_on_base`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `buildings_on_base` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `buildings_on_base` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `buildings_count` INT(11) UNSIGNED NOT NULL,
  `building_id` INT(11) UNSIGNED NOT NULL,
  `base_id` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_buildings_on_base_building1_idx1` (`building_id` ASC),
  INDEX `fk_buildings_on_base_base1_idx` (`base_id` ASC),
  CONSTRAINT `fk_buildings_on_base_base1`
    FOREIGN KEY (`base_id`)
    REFERENCES `base` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_buildings_on_base_building1`
    FOREIGN KEY (`building_id`)
    REFERENCES `building` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

DELIMITER $$

DROP TRIGGER IF EXISTS `building_BINS` $$
SHOW WARNINGS$$
CREATE TRIGGER `building_BINS` BEFORE INSERT ON `building` FOR EACH ROW
BEGIN
	SET NEW.modified = NOW();
END;
$$

SHOW WARNINGS$$

DROP TRIGGER IF EXISTS `building_BUPD` $$
SHOW WARNINGS$$
CREATE TRIGGER `building_BUPD` BEFORE UPDATE ON `building` FOR EACH ROW
BEGIN
	SET NEW.modified = NOW();
END;
$$

SHOW WARNINGS$$

DELIMITER ;

