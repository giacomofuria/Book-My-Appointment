CREATE DATABASE IF NOT EXISTS `book_my_appointment`; 
use `book_my_appointment`;

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  PRIMARY KEY (`userId`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

CREATE TABLE StrutturaTabellaAppuntamenti (
    userId int(11) NOT NULL,
    lun BOOLEAN DEFAULT FALSE,
    mar BOOLEAN DEFAULT FALSE,
    mer BOOLEAN DEFAULT FALSE,
    gio BOOLEAN DEFAULT FALSE,
    ven BOOLEAN DEFAULT FALSE,
    sab BOOLEAN DEFAULT FALSE,
    dom BOOLEAN DEFAULT FALSE,
    oraInizio int(11) DEFAULT NULL,
    oraFine int(11) DEFAULT NULL,
    durataIntervalli int(11) DEFAULT 60,
    intervalliPausa VARCHAR(100) DEFAULT NULL,
    PRIMARY KEY (userId),
    CONSTRAINT userId FOREIGN KEY (userID)
    REFERENCES user(userId)
    ON UPDATE NO ACTION
    ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;