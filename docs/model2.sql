SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Table `utilisateur`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `utilisateur` ;

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `idutilisateur` INT NOT NULL AUTO_INCREMENT,
  `fb` VARCHAR(255) NULL,
  `tw` VARCHAR(255) NULL,
  `google` VARCHAR(255) NULL,
  `nom` VARCHAR(255) NULL,
  `prenom` VARCHAR(255) NULL,
  `pseudo` VARCHAR(255) NULL,
  PRIMARY KEY (`idutilisateur`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `partenaire`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `partenaire` ;

CREATE TABLE IF NOT EXISTS `partenaire` (
  `idpartenaire` INT NOT NULL AUTO_INCREMENT,
  `nompartenaire` VARCHAR(100) NULL,
  `fbpartenaire` VARCHAR(255) NULL,
  `twpartenaire` VARCHAR(255) NULL,
  `goopartenaire` VARCHAR(255) NULL,
  `urlpartenaire` VARCHAR(255) NULL,
  `logopartenaire` VARCHAR(255) NULL,
  `descriptionpartenaire` VARCHAR(255) NULL,
  PRIMARY KEY (`idpartenaire`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `question`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `question` ;

CREATE TABLE IF NOT EXISTS `question` (
  `idquestion` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(500) NOT NULL,
  `reponse` VARCHAR(255) NOT NULL,
  `erreur1` VARCHAR(255) NOT NULL,
  `erreur2` VARCHAR(255) NOT NULL,
  `erreur3` VARCHAR(255) NOT NULL,
  `url` VARCHAR(255) NULL,
  PRIMARY KEY (`idquestion`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gain`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gain` ;

CREATE TABLE IF NOT EXISTS `gain` (
  `idgain` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(45) NOT NULL,
  `information` VARCHAR(45) NULL,
  `partenaire_idpartenaire` INT NOT NULL,
  PRIMARY KEY (`idgain`))
ENGINE = InnoDB;

CREATE INDEX `fk_gain_partenaire1_idx` ON `gain` (`partenaire_idpartenaire` ASC);


-- -----------------------------------------------------
-- Table `quizz`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `quizz` ;

CREATE TABLE IF NOT EXISTS `quizz` (
  `idquizz` INT NOT NULL AUTO_INCREMENT,
  `datedebut` DATETIME NULL,
  `datefin` DATETIME NULL,
  `estrepondu` TINYINT(1) NULL,
  `partenaire_idpartenaire` INT NOT NULL,
  `question_idquestion` INT NOT NULL,
  `gain_idgain` INT NULL,
  `utilisateur_idutilisateur` INT NULL,
  PRIMARY KEY (`idquizz`))
ENGINE = InnoDB;

CREATE INDEX `fk_quizz_partenaire1_idx` ON `quizz` (`partenaire_idpartenaire` ASC);

CREATE INDEX `fk_quizz_question1_idx` ON `quizz` (`question_idquestion` ASC);

CREATE INDEX `fk_quizz_gain1_idx` ON `quizz` (`gain_idgain` ASC);

CREATE INDEX `fk_quizz_utilisateur1_idx` ON `quizz` (`utilisateur_idutilisateur` ASC);


-- -----------------------------------------------------
-- Table `gare`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gare` ;

CREATE TABLE IF NOT EXISTS `gare` (
  `idgare` INT NOT NULL AUTO_INCREMENT,
  `uic` VARCHAR(45) NOT NULL,
  `nomgare` VARCHAR(255) NOT NULL,
  `region` VARCHAR(255) NULL,
  PRIMARY KEY (`idgare`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `participation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `participation` ;

CREATE TABLE IF NOT EXISTS `participation` (
  `idutilisateur` INT NOT NULL,
  `idquizz` INT NOT NULL,
  PRIMARY KEY (`idutilisateur`, `idquizz`))
ENGINE = InnoDB;

CREATE INDEX `fk_utilisateur_has_quizz_quizz1_idx` ON `participation` (`idquizz` ASC);

CREATE INDEX `fk_utilisateur_has_quizz_utilisateur_idx` ON `participation` (`idutilisateur` ASC);


-- -----------------------------------------------------
-- Table `gare_has_partenaire`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gare_has_partenaire` ;

CREATE TABLE IF NOT EXISTS `gare_has_partenaire` (
  `gare_idgare` INT NOT NULL,
  `partenaire_idpartenaire` INT NOT NULL)
ENGINE = InnoDB;

CREATE INDEX `fk_gare_has_partenaire_partenaire1_idx` ON `gare_has_partenaire` (`partenaire_idpartenaire` ASC);

CREATE INDEX `fk_gare_has_partenaire_gare1_idx` ON `gare_has_partenaire` (`gare_idgare` ASC);


-- -----------------------------------------------------
-- Table `gare_has_quizz`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gare_has_quizz` ;

CREATE TABLE IF NOT EXISTS `gare_has_quizz` (
  `gare_idgare` INT NOT NULL,
  `quizz_idquizz` INT NOT NULL)
ENGINE = InnoDB;

CREATE INDEX `fk_gare_has_quizz_quizz1_idx` ON `gare_has_quizz` (`quizz_idquizz` ASC);

CREATE INDEX `fk_gare_has_quizz_gare1_idx` ON `gare_has_quizz` (`gare_idgare` ASC);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
