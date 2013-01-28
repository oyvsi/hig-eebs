DROP DATABASE hig_eebs;

CREATE DATABASE hig_eebs;

USE hig_eebs;


CREATE TABLE bruker ( 
	brukerID int(32) NOT NULL AUTO_INCREMENT, 
	brukernavn varchar(32) NOT NULL, 
	fornavn varchar(32) NOT NULL, 
	etternavn varchar(32) NOT NULL, 
	passord varchar(10) NOT NULL, 
	epost varchar(64) NOT NULL, 
	bildeID int(64) NULL, 
	aktivert tinyint(1) NOT NULL,
	PRIMARY KEY (brukerID),
	FOREIGN KEY (bildeID) REFERENCES bilde(bildeID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE blogg (
	bloggID int(32) NOT NULL AUTO_INCREMENT, 
	brukerID int(32) NOT NULL, 
	brukernivaa varchar(16) NOT NULL, 
	tittel varchar(32) NOT NULL, 
	beskrivelse varchar(MAX),
	PRIMARY KEY (bloggID),
	FOREIGN KEY (brukerID) REFERENCES bruker(brukerID),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE bloggpost (
	postID int(32) NOT NULL AUTO_INCREMENT,
	bloggID int(32) NOT NULL,
	brukerID int(32) NOT NULL, 
	timestamp int(10) NOT NULL, 
	readcount int(128) NOT NULL, 
	kommentarcound int(128) NOT NULL,
	posttekst varchar(MAX) NOT NULL,
	PRIMARY KEY(postID),
	FOREIGN KEY(bloggID) REFERENCES blogg(bloggID),
	FOREIGN KEY(brukerID) REFERENCES bruker(brukerID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE kommentar  (
	kommentarID int(32) NOT NULL AUTO_INCREMENT, 
	brukerID int(32) NOT NULL, 
	bloggpostID int(32) NOT NULL, 
	timestamp int(10) NOT NULL, 
	source varchar(256) NULL,
	kommentar varchar(MAX),
	PRIMARY KEY (kommentarID),
	FOREIGN KEY brukerID REFERENCES bruker(brukerID),
	FOREIGN KEY bloggpostID REFERENCES bloggpost(postID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE bilde (
	bildeID int(32) NOT NULL AUTO_INCREMENT,
	brukerID int(32) NOT NULL,
	url varchar(1024) NOT NULL,
	timestamp int(10) NOT NULL,
	PRIMARY KEY (bildeID),
	FOREIGN KEY brukerID REFERENCES bruker(brukerID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;