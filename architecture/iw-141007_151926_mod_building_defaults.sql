SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `auth_assignment` 
DROP FOREIGN KEY `auth_assignment_ibfk_1`;

ALTER TABLE `auth_item` 
DROP FOREIGN KEY `auth_item_ibfk_1`;

ALTER TABLE `auth_item_child` 
DROP FOREIGN KEY `auth_item_child_ibfk_1`,
DROP FOREIGN KEY `auth_item_child_ibfk_2`;

ALTER TABLE `auth_assignment` 
COLLATE = utf8_unicode_ci ,
CHANGE COLUMN `item_name` `item_name` VARCHAR(64) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
CHANGE COLUMN `user_id` `user_id` VARCHAR(64) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ;

ALTER TABLE `auth_item` 
COLLATE = utf8_unicode_ci ,
CHANGE COLUMN `name` `name` VARCHAR(64) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
CHANGE COLUMN `description` `description` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
CHANGE COLUMN `rule_name` `rule_name` VARCHAR(64) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
CHANGE COLUMN `data` `data` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
ADD INDEX `idx-auth_item-type` (`type` ASC),
DROP INDEX `type` ;

ALTER TABLE `auth_item_child` 
COLLATE = utf8_unicode_ci ,
CHANGE COLUMN `parent` `parent` VARCHAR(64) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
CHANGE COLUMN `child` `child` VARCHAR(64) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ;

ALTER TABLE `auth_rule` 
COLLATE = utf8_unicode_ci ,
CHANGE COLUMN `name` `name` VARCHAR(64) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
CHANGE COLUMN `data` `data` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ;

ALTER TABLE `building` 
CHANGE COLUMN `cost_iron` `cost_iron` INT(11) UNSIGNED NOT NULL DEFAULT 100 ,
CHANGE COLUMN `cost_steel` `cost_steel` INT(11) UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `cost_chemicals` `cost_chemicals` INT(11) UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `cost_vv4a` `cost_vv4a` INT(11) UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `cost_ice` `cost_ice` INT(11) UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `cost_water` `cost_water` INT(11) UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `cost_energy` `cost_energy` INT(11) UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `cost_people` `cost_people` INT(11) UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `cost_credits` `cost_credits` INT(11) UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `cost_time` `cost_time` TIME NOT NULL DEFAULT '01:00:00' ,
CHANGE COLUMN `balance_iron` `balance_iron` INT(11) NOT NULL DEFAULT 0 ,
CHANGE COLUMN `balance_steel` `balance_steel` INT(11) NOT NULL DEFAULT 0 ,
CHANGE COLUMN `balance_chemicals` `balance_chemicals` INT(11) NOT NULL DEFAULT 0 ,
CHANGE COLUMN `balance_vv4a` `balance_vv4a` INT(11) NOT NULL DEFAULT 0 ,
CHANGE COLUMN `balance_ice` `balance_ice` INT(11) NOT NULL DEFAULT 0 ,
CHANGE COLUMN `balance_water` `balance_water` INT(11) NOT NULL DEFAULT 0 ,
CHANGE COLUMN `balance_energy` `balance_energy` INT(11) NOT NULL DEFAULT 0 ,
CHANGE COLUMN `balance_people` `balance_people` INT(11) NOT NULL DEFAULT 0 ,
CHANGE COLUMN `balance_credits` `balance_credits` FLOAT(11) NOT NULL DEFAULT 0.0 COMMENT 'Modifier for taxes income.' /* comment truncated */ /*1.5 means tax income increased by 50%.
0.5 means tax income decreased by 50%*/ ,
CHANGE COLUMN `balance_satisfaction` `balance_satisfaction` FLOAT(11) NOT NULL DEFAULT 0.0 ,
CHANGE COLUMN `storage_chemicals` `storage_chemicals` INT(10) UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `storage_ice_and_water` `storage_ice_and_water` INT(10) UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `storage_energy` `storage_energy` INT(10) UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `shelter_iron` `shelter_iron` INT(11) UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `shelter_steel` `shelter_steel` INT(11) UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `shelter_chemicals` `shelter_chemicals` INT(11) UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `shelter_vv4a` `shelter_vv4a` INT(11) UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `shelter_ice_and_water` `shelter_ice_and_water` INT(11) UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `shelter_energy` `shelter_energy` INT(11) UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `shelter_people` `shelter_people` INT(11) UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `highscore_points` `highscore_points` SMALLINT(5) UNSIGNED NOT NULL DEFAULT 10 ,
ADD COLUMN `limit` SMALLINT(5) UNSIGNED NULL DEFAULT NULL COMMENT 'The maximum number of buildings of this type that can be built on a planet.' AFTER `modified` ,
ADD UNIQUE INDEX `name_UNIQUE` (`name` ASC);

ALTER TABLE `auth_assignment` 
ADD CONSTRAINT `auth_assignment_ibfk_1`
  FOREIGN KEY (`item_name`)
  REFERENCES `auth_item` (`name`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `auth_item` 
ADD CONSTRAINT `auth_item_ibfk_1`
  FOREIGN KEY (`rule_name`)
  REFERENCES `auth_rule` (`name`)
  ON DELETE SET NULL
  ON UPDATE CASCADE;

ALTER TABLE `auth_item_child` 
ADD CONSTRAINT `auth_item_child_ibfk_1`
  FOREIGN KEY (`parent`)
  REFERENCES `auth_item` (`name`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `auth_item_child_ibfk_2`
  FOREIGN KEY (`child`)
  REFERENCES `auth_item` (`name`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
