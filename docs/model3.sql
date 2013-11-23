SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `pauseter` ;
CREATE SCHEMA IF NOT EXISTS `pauseter` DEFAULT CHARACTER SET latin1 ;
USE `pauseter` ;

-- -----------------------------------------------------
-- Table `partenaire`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `partenaire` ;

CREATE TABLE IF NOT EXISTS `partenaire` (
  `idPartenaire` INT(11) NOT NULL AUTO_INCREMENT,
  `nomPartenaire` VARCHAR(100) NULL DEFAULT NULL,
  `fbPartenaire` VARCHAR(255) NULL DEFAULT NULL,
  `twPartenaire` VARCHAR(255) NULL DEFAULT NULL,
  `gooPartenaire` VARCHAR(255) NULL DEFAULT NULL,
  `urlPartenaire` VARCHAR(255) NULL DEFAULT NULL,
  `logoPartenaire` VARCHAR(255) NULL DEFAULT NULL,
  `descPartenaire` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`idPartenaire`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `gain`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gain` ;

CREATE TABLE IF NOT EXISTS `gain` (
  `idGain` INT(11) NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(255) NOT NULL,
  `information` VARCHAR(255) NULL DEFAULT NULL,
  `idPartenaire` INT(11) NOT NULL,
  PRIMARY KEY (`idGain`),
  CONSTRAINT `fk_gain_partenaire1`
    FOREIGN KEY (`idPartenaire`)
    REFERENCES `partenaire` (`idPartenaire`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `fk_gain_partenaire1_idx` ON `gain` (`idPartenaire` ASC);


-- -----------------------------------------------------
-- Table `gare`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gare` ;

CREATE TABLE IF NOT EXISTS `gare` (
  `idGare` INT(11) NOT NULL AUTO_INCREMENT,
  `uic` VARCHAR(45) NOT NULL,
  `nomgare` VARCHAR(255) NOT NULL,
  `region` VARCHAR(255) NULL DEFAULT NULL,
  `tvs` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`idGare`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `participant`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `participant` ;

CREATE TABLE IF NOT EXISTS `participant` (
  `idParticipant` INT(11) NOT NULL AUTO_INCREMENT,
  `fb` VARCHAR(255) NULL DEFAULT NULL,
  `tw` VARCHAR(255) NULL DEFAULT NULL,
  `google` VARCHAR(255) NULL DEFAULT NULL,
  `nom` VARCHAR(255) NULL DEFAULT NULL,
  `prenom` VARCHAR(255) NULL DEFAULT NULL,
  `pseudo` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`idParticipant`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `question`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `question` ;

CREATE TABLE IF NOT EXISTS `question` (
  `idQuestion` INT(11) NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(1000) NOT NULL,
  `reponse` VARCHAR(255) NOT NULL,
  `erreur1` VARCHAR(255) NOT NULL,
  `erreur2` VARCHAR(255) NOT NULL,
  `erreur3` VARCHAR(255) NOT NULL,
  `url` VARCHAR(255) NULL DEFAULT NULL,
  `type` VARCHAR(255) NULL DEFAULT 'wikipedia',
  PRIMARY KEY (`idQuestion`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `quizz`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `quizz` ;

CREATE TABLE IF NOT EXISTS `quizz` (
  `idQuizz` INT(11) NOT NULL AUTO_INCREMENT,
  `dateDebut` DATETIME NULL DEFAULT NULL,
  `dateFin` DATETIME NULL DEFAULT NULL,
  `estRepondu` TINYINT(1) NULL DEFAULT NULL,
  `idPartenaire` INT(11) NOT NULL,
  `idQuestion` INT(11) NOT NULL,
  `idGain` INT(11) NULL DEFAULT NULL,
  `idParticipant` INT(11) NULL DEFAULT NULL,
  `idGare` INT(11) NOT NULL,
  PRIMARY KEY (`idQuizz`),
  CONSTRAINT `fk_quizz_partenaire1`
    FOREIGN KEY (`idPartenaire`)
    REFERENCES `partenaire` (`idPartenaire`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_quizz_question1`
    FOREIGN KEY (`idQuestion`)
    REFERENCES `question` (`idQuestion`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_quizz_gain1`
    FOREIGN KEY (`idGain`)
    REFERENCES `gain` (`idGain`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_quizz_utilisateur1`
    FOREIGN KEY (`idParticipant`)
    REFERENCES `participant` (`idParticipant`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_quizz_gare1`
    FOREIGN KEY (`idGare`)
    REFERENCES `gare` (`idGare`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `fk_quizz_partenaire1_idx` ON `quizz` (`idPartenaire` ASC);

CREATE INDEX `fk_quizz_question1_idx` ON `quizz` (`idQuestion` ASC);

CREATE INDEX `fk_quizz_gain1_idx` ON `quizz` (`idGain` ASC);

CREATE INDEX `fk_quizz_utilisateur1_idx` ON `quizz` (`idParticipant` ASC);

CREATE INDEX `fk_quizz_gare1_idx` ON `quizz` (`idGare` ASC);


-- -----------------------------------------------------
-- Table `participer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `participer` ;

CREATE TABLE IF NOT EXISTS `participer` (
  `idParticipant` INT(11) NOT NULL,
  `idQuizz` INT(11) NOT NULL,
  PRIMARY KEY (`idParticipant`, `idQuizz`),
  CONSTRAINT `fk_utilisateur_has_quizz_utilisateur`
    FOREIGN KEY (`idParticipant`)
    REFERENCES `participant` (`idParticipant`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_utilisateur_has_quizz_quizz1`
    FOREIGN KEY (`idQuizz`)
    REFERENCES `quizz` (`idQuizz`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `fk_utilisateur_has_quizz_quizz1_idx` ON `participer` (`idQuizz` ASC);

CREATE INDEX `fk_utilisateur_has_quizz_utilisateur_idx` ON `participer` (`idParticipant` ASC);


-- -----------------------------------------------------
-- Table `situer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `situer` ;

CREATE TABLE IF NOT EXISTS `situer` (
  `idGare` INT(11) NOT NULL,
  `idPartenaire` INT(11) NOT NULL,
  PRIMARY KEY (`idGare`, `idPartenaire`),
  CONSTRAINT `fk_gare_has_partenaire_gare1`
    FOREIGN KEY (`idGare`)
    REFERENCES `gare` (`idGare`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_gare_has_partenaire_partenaire1`
    FOREIGN KEY (`idPartenaire`)
    REFERENCES `partenaire` (`idPartenaire`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `fk_gare_has_partenaire_partenaire1_idx` ON `situer` (`idPartenaire` ASC);

CREATE INDEX `fk_gare_has_partenaire_gare1_idx` ON `situer` (`idGare` ASC);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
