CREATE DATABASE IF NOT EXISTS `book_my_appointment`; 
use `book_my_appointment`;

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  profile_image MEDIUMBLOB DEFAULT NULL,
  profession VARCHAR(100) DEFAULT NULL,
  address VARCHAR(100) DEFAULT NULL,
  admin BOOLEAN DEFAULT FALSE,
  PRIMARY KEY (`userId`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS struttura_tabella_appuntamenti;
CREATE TABLE struttura_tabella_appuntamenti (
    userId int(11) NOT NULL,
    giorni VARCHAR(30) DEFAULT NULL,
    oraInizio VARCHAR(10) DEFAULT NULL,
    oraFine VARCHAR(10) DEFAULT NULL,
    durataIntervalli int(11) DEFAULT 60,
    intervalliPausa VARCHAR(100) DEFAULT NULL,
    PRIMARY KEY (userId),
    CONSTRAINT userId FOREIGN KEY (userID)
    REFERENCES user(userId)
    ON UPDATE NO ACTION
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS appuntamento;
CREATE TABLE appuntamento(
    idAppuntamento INT(11) NOT NULL AUTO_INCREMENT,
    idRichiedente INT(11) NOT NULL,
    idRicevente INT(11) NOT NULL,
    dataOra TIMESTAMP NOT NULL,
    durata INT(11) NOT NULL,
    note MEDIUMTEXT DEFAULT NULL,
    PRIMARY KEY (idAppuntamento),
    UNIQUE (idRichiedente,idRicevente,dataOra),
    CONSTRAINT vincolo_utente_ricevente FOREIGN KEY (idRicevente)
    REFERENCES user(userId)
    ON UPDATE NO ACTION
    ON DELETE CASCADE,
    CONSTRAINT vincolo_utente_richiedente FOREIGN KEY (idRichiedente)
    REFERENCES user(userId)
    ON UPDATE NO ACTION
    ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS recensione;
CREATE TABLE recensione(
    idRecensione INT(11) NOT NULL AUTO_INCREMENT,
    idRicevente INT(11) NOT NULL,
    idRecensore INT(11) NOT NULL,
    dataOra TIMESTAMP NOT NULL,
    punteggio INT(11) NOT NULL,
    testoRecensione MEDIUMTEXT DEFAULT NULL,
    PRIMARY KEY (idRecensione),
    CONSTRAINT vincolo_utente_ricevente_recensione FOREIGN KEY (idRicevente)
    REFERENCES user(userId)
    ON UPDATE NO ACTION
    ON DELETE CASCADE,
    CONSTRAINT vincolo_utente_recensore FOREIGN KEY (idRecensore)
    REFERENCES user(userId)
    ON UPDATE NO ACTION
    ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS notifica;
CREATE TABLE notifica(
   idNotifica INT(11) NOT NULL AUTO_INCREMENT,
   idDestinatario INT(11) NOT NULL,
   testo MEDIUMTEXT DEFAULT NULL,
   letta BOOLEAN DEFAULT FALSE,
   PRIMARY KEY (idNotifica),
   CONSTRAINT vincolo_utente_destinatario FOREIGN KEY (idDestinatario)
    REFERENCES user(userId)
    ON UPDATE NO ACTION
    ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=latin1;