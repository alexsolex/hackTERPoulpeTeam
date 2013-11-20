SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Table `participant`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `participant` ;

CREATE TABLE IF NOT EXISTS `participant` (
  `idParticipant` INT NOT NULL AUTO_INCREMENT,
  `fb` VARCHAR(255) NULL,
  `tw` VARCHAR(255) NULL,
  `google` VARCHAR(255) NULL,
  `nom` VARCHAR(255) NULL,
  `prenom` VARCHAR(255) NULL,
  `pseudo` VARCHAR(255) NULL,
  PRIMARY KEY (`idParticipant`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `partenaire`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `partenaire` ;

CREATE TABLE IF NOT EXISTS `partenaire` (
  `idPartenaire` INT NOT NULL AUTO_INCREMENT,
  `nomPartenaire` VARCHAR(100) NULL,
  `fbPartenaire` VARCHAR(255) NULL,
  `twPartenaire` VARCHAR(255) NULL,
  `gooPartenaire` VARCHAR(255) NULL,
  `urlPartenaire` VARCHAR(255) NULL,
  `logoPartenaire` VARCHAR(255) NULL,
  `descPartenaire` VARCHAR(255) NULL,
  PRIMARY KEY (`idPartenaire`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `question`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `question` ;

CREATE TABLE IF NOT EXISTS `question` (
  `idQuestion` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(1000) NOT NULL,
  `reponse` VARCHAR(255) NOT NULL,
  `erreur1` VARCHAR(255) NOT NULL,
  `erreur2` VARCHAR(255) NOT NULL,
  `erreur3` VARCHAR(255) NOT NULL,
  `url` VARCHAR(255) NULL,
  `type` VARCHAR(255) NULL DEFAULT 'wikipedia',
  PRIMARY KEY (`idQuestion`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gain`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gain` ;

CREATE TABLE IF NOT EXISTS `gain` (
  `idGain` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(255) NOT NULL,
  `information` VARCHAR(255) NULL,
  `idPartenaire` INT NOT NULL,
  PRIMARY KEY (`idGain`),
  CONSTRAINT `fk_gain_partenaire1`
    FOREIGN KEY (`idPartenaire`)
    REFERENCES `partenaire` (`idPartenaire`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_gain_partenaire1_idx` ON `gain` (`idPartenaire` ASC);


-- -----------------------------------------------------
-- Table `quizz`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `quizz` ;

CREATE TABLE IF NOT EXISTS `quizz` (
  `idQuizz` INT NOT NULL AUTO_INCREMENT,
  `dateDebut` DATETIME NULL,
  `dateFin` DATETIME NULL,
  `estRepondu` TINYINT(1) NULL,
  `idPartenaire` INT NOT NULL,
  `idQuestion` INT NOT NULL,
  `idGain` INT NULL,
  `idParticipant` INT NULL,
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
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_quizz_partenaire1_idx` ON `quizz` (`idPartenaire` ASC);

CREATE INDEX `fk_quizz_question1_idx` ON `quizz` (`idQuestion` ASC);

CREATE INDEX `fk_quizz_gain1_idx` ON `quizz` (`idGain` ASC);

CREATE INDEX `fk_quizz_utilisateur1_idx` ON `quizz` (`idParticipant` ASC);


-- -----------------------------------------------------
-- Table `gare`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gare` ;

CREATE TABLE IF NOT EXISTS `gare` (
  `idGare` INT NOT NULL AUTO_INCREMENT,
  `uic` VARCHAR(45) NOT NULL,
  `nomgare` VARCHAR(255) NOT NULL,
  `region` VARCHAR(255) NULL,
  `tvs` VARCHAR(45) NULL,
  PRIMARY KEY (`idGare`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `participer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `participer` ;

CREATE TABLE IF NOT EXISTS `participer` (
  `idParticipant` INT NOT NULL,
  `idQuizz` INT NOT NULL,
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
ENGINE = InnoDB;

CREATE INDEX `fk_utilisateur_has_quizz_quizz1_idx` ON `participer` (`idQuizz` ASC);

CREATE INDEX `fk_utilisateur_has_quizz_utilisateur_idx` ON `participer` (`idParticipant` ASC);


-- -----------------------------------------------------
-- Table `situer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `situer` ;

CREATE TABLE IF NOT EXISTS `situer` (
  `idGare` INT NOT NULL,
  `idPartenaire` INT NOT NULL,
  PRIMARY KEY (`idPartenaire`, `idGare`),
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
ENGINE = InnoDB;

CREATE INDEX `fk_gare_has_partenaire_partenaire1_idx` ON `situer` (`idPartenaire` ASC);

CREATE INDEX `fk_gare_has_partenaire_gare1_idx` ON `situer` (`idGare` ASC);


-- -----------------------------------------------------
-- Table `gare_has_quizz`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gare_has_quizz` ;

CREATE TABLE IF NOT EXISTS `gare_has_quizz` (
  `idGare` INT NOT NULL,
  `idQuizz` INT NOT NULL,
  PRIMARY KEY (`idQuizz`, `idGare`),
  CONSTRAINT `fk_gare_has_quizz_gare1`
    FOREIGN KEY (`idGare`)
    REFERENCES `gare` (`idGare`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_gare_has_quizz_quizz1`
    FOREIGN KEY (`idQuizz`)
    REFERENCES `quizz` (`idQuizz`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_gare_has_quizz_quizz1_idx` ON `gare_has_quizz` (`idQuizz` ASC);

CREATE INDEX `fk_gare_has_quizz_gare1_idx` ON `gare_has_quizz` (`idGare` ASC);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
