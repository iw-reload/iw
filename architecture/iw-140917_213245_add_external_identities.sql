SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

ALTER TABLE `default_schema`.`user` 
DROP COLUMN `status`,
DROP COLUMN `role`,
DROP COLUMN `email`,
DROP COLUMN `password_reset_token`,
DROP COLUMN `password_hash`,
DROP COLUMN `auth_key`,
DROP COLUMN `username`,
ADD COLUMN `name` VARCHAR(255) NOT NULL AFTER `id`,
ADD UNIQUE INDEX `name_UNIQUE` (`name` ASC);

CREATE TABLE IF NOT EXISTS `default_schema`.`identity` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `internal_user_id` INT(11) UNSIGNED NOT NULL,
  `auth_provider` VARCHAR(32) NOT NULL,
  `external_user_id` VARCHAR(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `unique_external_id` (`auth_provider` ASC, `external_user_id` ASC),
  INDEX `fk_identity_user1_idx` (`internal_user_id` ASC),
  CONSTRAINT `fk_identity_user1`
    FOREIGN KEY (`internal_user_id`)
    REFERENCES `default_schema`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
