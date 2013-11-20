CREATE DATABASE  IF NOT EXISTS `pauseter` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `pauseter`;
-- MySQL dump 10.13  Distrib 5.6.13, for Win32 (x86)
--
-- Host: 127.0.0.1    Database: pauseter
-- ------------------------------------------------------
-- Server version	5.6.12-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `gain`
--

DROP TABLE IF EXISTS `gain`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gain` (
  `idGain` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) NOT NULL,
  `information` varchar(255) DEFAULT NULL,
  `idPartenaire` int(11) NOT NULL,
  PRIMARY KEY (`idGain`),
  KEY `fk_gain_partenaire1_idx` (`idPartenaire`),
  CONSTRAINT `fk_gain_partenaire1` FOREIGN KEY (`idPartenaire`) REFERENCES `partenaire` (`idPartenaire`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gain`
--

LOCK TABLES `gain` WRITE;
/*!40000 ALTER TABLE `gain` DISABLE KEYS */;
INSERT INTO `gain` VALUES (1,'un café machiatto','du café avec du sucre valable ce jour',2),(2,'un café noir','le célèbre petit noir',2),(3,'une réduction de 10€','sur un produit TER acheté dans cette gare',1),(4,'une clé usb','d\'une capacité de 8Go',1);
/*!40000 ALTER TABLE `gain` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gare`
--

DROP TABLE IF EXISTS `gare`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gare` (
  `idGare` int(11) NOT NULL AUTO_INCREMENT,
  `uic` varchar(45) NOT NULL,
  `nomgare` varchar(255) NOT NULL,
  `region` varchar(255) DEFAULT NULL,
  `tvs` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idGare`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gare`
--

LOCK TABLES `gare` WRITE;
/*!40000 ALTER TABLE `gare` DISABLE KEYS */;
INSERT INTO `gare` VALUES (1,'123','Orchies','Nord-Pas-De-Calais','OCH'),(2,'234','Lille Flandres','Nord-Pas-De-Calais','LEW');
/*!40000 ALTER TABLE `gare` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partenaire`
--

DROP TABLE IF EXISTS `partenaire`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partenaire` (
  `idPartenaire` int(11) NOT NULL AUTO_INCREMENT,
  `nomPartenaire` varchar(100) DEFAULT NULL,
  `fbPartenaire` varchar(255) DEFAULT NULL,
  `twPartenaire` varchar(255) DEFAULT NULL,
  `gooPartenaire` varchar(255) DEFAULT NULL,
  `urlPartenaire` varchar(255) DEFAULT NULL,
  `logoPartenaire` varchar(255) DEFAULT NULL,
  `descPartenaire` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idPartenaire`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partenaire`
--

LOCK TABLES `partenaire` WRITE;
/*!40000 ALTER TABLE `partenaire` DISABLE KEYS */;
INSERT INTO `partenaire` VALUES (1,'TER',NULL,NULL,NULL,'www.ter.sncf.fr','ter.png','TER SNCF'),(2,'Starbuck',NULL,NULL,NULL,'www.starbuck.Fr','Starbucks.png','Starbuck vous propose des cafés dans cette gare');
/*!40000 ALTER TABLE `partenaire` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participant`
--

DROP TABLE IF EXISTS `participant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `participant` (
  `idParticipant` int(11) NOT NULL AUTO_INCREMENT,
  `fb` varchar(255) DEFAULT NULL,
  `tw` varchar(255) DEFAULT NULL,
  `google` varchar(255) DEFAULT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `pseudo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idParticipant`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participant`
--

LOCK TABLES `participant` WRITE;
/*!40000 ALTER TABLE `participant` DISABLE KEYS */;
INSERT INTO `participant` VALUES (1,NULL,'@alexsolex',NULL,'perret','alexandre','alexsolex'),(2,NULL,NULL,NULL,'horn','fred','fredo'),(3,NULL,NULL,NULL,'catel','thierry','titi'),(4,NULL,'@bootsymayfield',NULL,'soyris','laurent','lolo');
/*!40000 ALTER TABLE `participant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participer`
--

DROP TABLE IF EXISTS `participer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `participer` (
  `idParticipant` int(11) NOT NULL,
  `idQuizz` int(11) NOT NULL,
  PRIMARY KEY (`idParticipant`,`idQuizz`),
  KEY `fk_utilisateur_has_quizz_quizz1_idx` (`idQuizz`),
  KEY `fk_utilisateur_has_quizz_utilisateur_idx` (`idParticipant`),
  CONSTRAINT `fk_utilisateur_has_quizz_utilisateur` FOREIGN KEY (`idParticipant`) REFERENCES `participant` (`idParticipant`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_utilisateur_has_quizz_quizz1` FOREIGN KEY (`idQuizz`) REFERENCES `quizz` (`idQuizz`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participer`
--

LOCK TABLES `participer` WRITE;
/*!40000 ALTER TABLE `participer` DISABLE KEYS */;
INSERT INTO `participer` VALUES (1,1),(2,1),(4,1),(2,2),(3,2);
/*!40000 ALTER TABLE `participer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `question`
--

DROP TABLE IF EXISTS `question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `question` (
  `idQuestion` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(1000) NOT NULL,
  `reponse` varchar(255) NOT NULL,
  `erreur1` varchar(255) NOT NULL,
  `erreur2` varchar(255) NOT NULL,
  `erreur3` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT 'wikipedia',
  PRIMARY KEY (`idQuestion`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question`
--

LOCK TABLES `question` WRITE;
/*!40000 ALTER TABLE `question` DISABLE KEYS */;
INSERT INTO `question` VALUES (1,'ceci est une question','ok','non','non2','non3',NULL,'wikipedia'),(2,'ceci est une autre question','bonne réponse','erreur','pas du tout','et non','http://un.lien.vers.des.infos','wikipedia'),(3,'une commune française, située dans le département du Nord (59) en région Nord-Pas-de-Calais.\nLe nom jeté des habitants est les pourchots1, signifiant « porc » en picard.','Orchies','Lille','Charleville','Paris','http://fr.wikipedia.org/wiki/Orchies','wikipedia'),(4,' une commune du nord de la France, préfecture du département du Nord et chef-lieu en région Nord-Pas-de-Calais. Surnommée la « Capitale des Flandres »','Lille','Orchies','Simcity','bruxelles','http://fr.wikipedia.org/wiki/Lille','wikipedia');
/*!40000 ALTER TABLE `question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quizz`
--

DROP TABLE IF EXISTS `quizz`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quizz` (
  `idQuizz` int(11) NOT NULL AUTO_INCREMENT,
  `dateDebut` datetime DEFAULT NULL,
  `dateFin` datetime DEFAULT NULL,
  `estRepondu` tinyint(1) DEFAULT NULL,
  `idPartenaire` int(11) NOT NULL,
  `idQuestion` int(11) NOT NULL,
  `idGain` int(11) DEFAULT NULL,
  `idParticipant` int(11) DEFAULT NULL,
  `idGare` int(11) NOT NULL,
  PRIMARY KEY (`idQuizz`),
  KEY `fk_quizz_partenaire1_idx` (`idPartenaire`),
  KEY `fk_quizz_question1_idx` (`idQuestion`),
  KEY `fk_quizz_gain1_idx` (`idGain`),
  KEY `fk_quizz_utilisateur1_idx` (`idParticipant`),
  KEY `fk_quizz_gare1_idx` (`idGare`),
  CONSTRAINT `fk_quizz_partenaire1` FOREIGN KEY (`idPartenaire`) REFERENCES `partenaire` (`idPartenaire`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_quizz_question1` FOREIGN KEY (`idQuestion`) REFERENCES `question` (`idQuestion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_quizz_gain1` FOREIGN KEY (`idGain`) REFERENCES `gain` (`idGain`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_quizz_utilisateur1` FOREIGN KEY (`idParticipant`) REFERENCES `participant` (`idParticipant`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_quizz_gare1` FOREIGN KEY (`idGare`) REFERENCES `gare` (`idGare`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quizz`
--

LOCK TABLES `quizz` WRITE;
/*!40000 ALTER TABLE `quizz` DISABLE KEYS */;
INSERT INTO `quizz` VALUES (1,'2013-11-15 12:00:00','2013-11-15 12:05:28',1,2,3,1,NULL,2),(2,'2013-11-15 12:06:00',null,0,2,4,2,NULL,2),(3,null,null,0,1,2,4,NULL,1);
/*!40000 ALTER TABLE `quizz` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `situer`
--

DROP TABLE IF EXISTS `situer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `situer` (
  `idGare` int(11) NOT NULL,
  `idPartenaire` int(11) NOT NULL,
  PRIMARY KEY (`idGare`,`idPartenaire`),
  KEY `fk_gare_has_partenaire_partenaire1_idx` (`idPartenaire`),
  KEY `fk_gare_has_partenaire_gare1_idx` (`idGare`),
  CONSTRAINT `fk_gare_has_partenaire_gare1` FOREIGN KEY (`idGare`) REFERENCES `gare` (`idGare`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_gare_has_partenaire_partenaire1` FOREIGN KEY (`idPartenaire`) REFERENCES `partenaire` (`idPartenaire`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `situer`
--

LOCK TABLES `situer` WRITE;
/*!40000 ALTER TABLE `situer` DISABLE KEYS */;
INSERT INTO `situer` VALUES (1,1),(2,1),(2,2);
/*!40000 ALTER TABLE `situer` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-11-20 20:50:54
