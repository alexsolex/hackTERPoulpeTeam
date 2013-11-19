
CREATE DATABASE IF NOT EXISTS pauseter;
USE pauseter;
# -----------------------------------------------------------------------------
#       TABLE : PARTICIPANT
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS PARTICIPANT
 (
   IDPARTICIPANT INTEGER(2) NOT NULL AUTO_INCREMENT ,
   FACEBOOK VARCHAR(255) NULL  ,
   TWITTER VARCHAR(255) NULL  ,
   GOOGLE VARCHAR(255) NULL  ,
   NOM VARCHAR(128) NULL  ,
   PRENOM VARCHAR(128) NULL  ,
   PSEUDO VARCHAR(128) NULL  
   , PRIMARY KEY (IDPARTICIPANT) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : GARE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS GARE
 (
   IDGARE INTEGER(2) NOT NULL AUTO_INCREMENT ,
   CODEUIC VARCHAR(128) NULL  ,
   NOMGARE VARCHAR(128) NULL  ,
   REGION VARCHAR(128) NULL  ,
   TVS VARCHAR(128) NULL  ,
   CODEUIC8 VARCHAR(128) NULL  ,
   CODEQLT VARCHAR(128) NULL  
   , PRIMARY KEY (IDGARE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : GAIN
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS GAIN
 (
   IDGAIN INTEGER(2) NOT NULL AUTO_INCREMENT ,
   LIBELLE VARCHAR(255) NULL  
   , PRIMARY KEY (IDGAIN) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : QUESTION
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS QUESTION
 (
   IDQUESTION INTEGER(2) NOT NULL AUTO_INCREMENT ,
   IDPARTENAIRE INTEGER(2) NULL  ,
   IDGARE INTEGER(2) NULL  ,
   IDGAIN INTEGER(2) NULL  ,
   IDPARTICIPANT INTEGER(2) NOT NULL  ,
   LIBELLE VARCHAR(128) NULL  ,
   DATEDEBUT DATETIME NULL  ,
   DATEFIN DATETIME NULL  ,
   REPONSE VARCHAR(128) NULL  ,
   ESTREPONDU BOOL NULL  ,
   BADREPONSE1 VARCHAR(128) NULL  ,
   BARREPONSE2 VARCHAR(128) NULL  ,
   BADREPONSE3 VARCHAR(128) NULL  
   , PRIMARY KEY (IDQUESTION) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE QUESTION
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_QUESTION_PARTENAIRE
     ON QUESTION (IDPARTENAIRE ASC);

CREATE  INDEX I_FK_QUESTION_GARE
     ON QUESTION (IDGARE ASC);

CREATE  INDEX I_FK_QUESTION_GAIN
     ON QUESTION (IDGAIN ASC);

CREATE  INDEX I_FK_QUESTION_PARTICIPANT
     ON QUESTION (IDPARTICIPANT ASC);

# -----------------------------------------------------------------------------
#       TABLE : PARTENAIRE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS PARTENAIRE
 (
   IDPARTENAIRE INTEGER(2) NOT NULL AUTO_INCREMENT ,
   IDGAIN INTEGER(2) NULL  ,
   LIEN VARCHAR(255) NULL  ,
   FB VARCHAR(255) NULL  ,
   TW VARCHAR(255) NULL  ,
   GOOGLE VARCHAR(255) NULL  ,
   LOGO VARCHAR(128) NULL  ,
   DESCRIPTION VARCHAR(255) NULL  
   , PRIMARY KEY (IDPARTENAIRE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE PARTENAIRE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_PARTENAIRE_GAIN
     ON PARTENAIRE (IDGAIN ASC);

# -----------------------------------------------------------------------------
#       TABLE : PARTICIPER
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS PARTICIPER
 (
   IDPARTICIPANT INTEGER(2) NOT NULL  ,
   IDQUESTION INTEGER(2) NOT NULL  
   , PRIMARY KEY (IDPARTICIPANT,IDQUESTION) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE PARTICIPER
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_PARTICIPER_PARTICIPANT
     ON PARTICIPER (IDPARTICIPANT ASC);

CREATE  INDEX I_FK_PARTICIPER_QUESTION
     ON PARTICIPER (IDQUESTION ASC);

# -----------------------------------------------------------------------------
#       TABLE : SITUER
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS SITUER
 (
   IDGARE INTEGER(2) NOT NULL  ,
   IDPARTENAIRE INTEGER(2) NOT NULL  
   , PRIMARY KEY (IDGARE,IDPARTENAIRE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE SITUER
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_SITUER_GARE
     ON SITUER (IDGARE ASC);

CREATE  INDEX I_FK_SITUER_PARTENAIRE
     ON SITUER (IDPARTENAIRE ASC);


# -----------------------------------------------------------------------------
#       CREATION DES REFERENCES DE TABLE
# -----------------------------------------------------------------------------


ALTER TABLE QUESTION 
  ADD FOREIGN KEY FK_QUESTION_PARTENAIRE (IDPARTENAIRE)
      REFERENCES PARTENAIRE (IDPARTENAIRE) ;


ALTER TABLE QUESTION 
  ADD FOREIGN KEY FK_QUESTION_GARE (IDGARE)
      REFERENCES GARE (IDGARE) ;


ALTER TABLE QUESTION 
  ADD FOREIGN KEY FK_QUESTION_GAIN (IDGAIN)
      REFERENCES GAIN (IDGAIN) ;


ALTER TABLE QUESTION 
  ADD FOREIGN KEY FK_QUESTION_PARTICIPANT (IDPARTICIPANT)
      REFERENCES PARTICIPANT (IDPARTICIPANT) ;


ALTER TABLE PARTENAIRE 
  ADD FOREIGN KEY FK_PARTENAIRE_GAIN (IDGAIN)
      REFERENCES GAIN (IDGAIN) ;


ALTER TABLE PARTICIPER 
  ADD FOREIGN KEY FK_PARTICIPER_PARTICIPANT (IDPARTICIPANT)
      REFERENCES PARTICIPANT (IDPARTICIPANT) ;


ALTER TABLE PARTICIPER 
  ADD FOREIGN KEY FK_PARTICIPER_QUESTION (IDQUESTION)
      REFERENCES QUESTION (IDQUESTION) ;


ALTER TABLE SITUER 
  ADD FOREIGN KEY FK_SITUER_GARE (IDGARE)
      REFERENCES GARE (IDGARE) ;


ALTER TABLE SITUER 
  ADD FOREIGN KEY FK_SITUER_PARTENAIRE (IDPARTENAIRE)
      REFERENCES PARTENAIRE (IDPARTENAIRE) ;
