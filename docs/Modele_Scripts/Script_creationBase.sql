
CREATE DATABASE IF NOT EXISTS pauseter;
USE pauseter;
# -----------------------------------------------------------------------------
#       TABLE : PARTICIPANT
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS PARTICIPANT
 (
   IDPARTICIPANT CHAR(32) NOT NULL  ,
   IDQUESTION CHAR(32) NOT NULL  ,
   FACEBOOK BOOL NULL  ,
   TWITTER BOOL NULL  ,
   GOOGLE BOOL NULL  ,
   NOM VARCHAR(128) NULL  ,
   PRENOM VARCHAR(128) NULL  ,
   PSEUDO VARCHAR(128) NULL  
   , PRIMARY KEY (IDPARTICIPANT) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE PARTICIPANT
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_PARTICIPANT_QUESTION
     ON PARTICIPANT (IDQUESTION ASC);

# -----------------------------------------------------------------------------
#       TABLE : GARE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS GARE
 (
   CODEUIC CHAR(32) NOT NULL  ,
   IDQUESTION CHAR(32) NOT NULL  ,
   NOMGARE VARCHAR(128) NULL  ,
   REGION VARCHAR(128) NULL  
   , PRIMARY KEY (CODEUIC) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE GARE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_GARE_QUESTION
     ON GARE (IDQUESTION ASC);

# -----------------------------------------------------------------------------
#       TABLE : GAIN
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS GAIN
 (
   IDGAIN CHAR(32) NOT NULL  ,
   IDQUESTION CHAR(32) NULL  ,
   LIBELLE VARCHAR(255) NULL  
   , PRIMARY KEY (IDGAIN) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE GAIN
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_GAIN_QUESTION
     ON GAIN (IDQUESTION ASC);

# -----------------------------------------------------------------------------
#       TABLE : QUESTION
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS QUESTION
 (
   IDQUESTION CHAR(32) NOT NULL  ,
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
#       TABLE : PARTENAIRE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS PARTENAIRE
 (
   IDPARTENAIRE CHAR(32) NOT NULL  ,
   IDGAIN CHAR(32) NULL  ,
   IDQUESTION CHAR(32) NULL  ,
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

CREATE  INDEX I_FK_PARTENAIRE_QUESTION
     ON PARTENAIRE (IDQUESTION ASC);

# -----------------------------------------------------------------------------
#       TABLE : PARTICIPER
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS PARTICIPER
 (
   IDPARTICIPANT CHAR(32) NOT NULL  ,
   IDQUESTION CHAR(32) NOT NULL  
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
   CODEUIC CHAR(32) NOT NULL  ,
   IDPARTENAIRE CHAR(32) NOT NULL  
   , PRIMARY KEY (CODEUIC,IDPARTENAIRE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE SITUER
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_SITUER_GARE
     ON SITUER (CODEUIC ASC);

CREATE  INDEX I_FK_SITUER_PARTENAIRE
     ON SITUER (IDPARTENAIRE ASC);


# -----------------------------------------------------------------------------
#       CREATION DES REFERENCES DE TABLE
# -----------------------------------------------------------------------------


ALTER TABLE PARTICIPANT 
  ADD FOREIGN KEY FK_PARTICIPANT_QUESTION (IDQUESTION)
      REFERENCES QUESTION (IDQUESTION) ;


ALTER TABLE GARE 
  ADD FOREIGN KEY FK_GARE_QUESTION (IDQUESTION)
      REFERENCES QUESTION (IDQUESTION) ;


ALTER TABLE GAIN 
  ADD FOREIGN KEY FK_GAIN_QUESTION (IDQUESTION)
      REFERENCES QUESTION (IDQUESTION) ;


ALTER TABLE PARTENAIRE 
  ADD FOREIGN KEY FK_PARTENAIRE_GAIN (IDGAIN)
      REFERENCES GAIN (IDGAIN) ;


ALTER TABLE PARTENAIRE 
  ADD FOREIGN KEY FK_PARTENAIRE_QUESTION (IDQUESTION)
      REFERENCES QUESTION (IDQUESTION) ;


ALTER TABLE PARTICIPER 
  ADD FOREIGN KEY FK_PARTICIPER_PARTICIPANT (IDPARTICIPANT)
      REFERENCES PARTICIPANT (IDPARTICIPANT) ;


ALTER TABLE PARTICIPER 
  ADD FOREIGN KEY FK_PARTICIPER_QUESTION (IDQUESTION)
      REFERENCES QUESTION (IDQUESTION) ;


ALTER TABLE SITUER 
  ADD FOREIGN KEY FK_SITUER_GARE (CODEUIC)
      REFERENCES GARE (CODEUIC) ;


ALTER TABLE SITUER 
  ADD FOREIGN KEY FK_SITUER_PARTENAIRE (IDPARTENAIRE)
      REFERENCES PARTENAIRE (IDPARTENAIRE) ;

