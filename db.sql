DROP DATABASE hig_eebs;

CREATE DATABASE hig_eebs;

USE hig_eebs;


CREATE TABLE users ( 
	userID int(32) NOT NULL AUTO_INCREMENT, 
	userName varchar(32) NOT NULL, 
	firstName varchar(32) NOT NULL, 
	lastName varchar(32) NOT NULL, 
	password varchar(255) NOT NULL, 
	email varchar(64) NOT NULL, 
	pictureID int(64) NULL, 
	userLevel tinyint(1) NOT NULL,
	activated tinyint(1) NOT NULL,
	PRIMARY KEY (userID),
	FOREIGN KEY (pictureID) REFERENCES pictures(pictureID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE blogs (
	blogID int(32) NOT NULL AUTO_INCREMENT, 
	userID int(32) NOT NULL, 
	tittel varchar(32) NOT NULL, 
	beskrivelse varchar(20000),
	PRIMARY KEY (blogID),
	FOREIGN KEY (userID) REFERENCES users(userID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE blogUsers (
	blogID int(32) NOT NULL,
	userID int(32) NOT NULL,
	userLevel tinyint(1) NOT NULL,
	PRIMARY KEY (blogID, userID),
	FOREIGN KEY (blogID) REFERENCES blog(blogID),
	FOREIGN KEY (userID) REFERENCES users(userID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE blogPosts (
	postID int(32) NOT NULL AUTO_INCREMENT,
	blogID int(32) NOT NULL,
	userID int(32) NOT NULL, 
	timestamp int(10) NOT NULL, 
	postText varchar(20000) NOT NULL,
	PRIMARY KEY(postID),
	FOREIGN KEY(blogID) REFERENCES blog(blogID),
	FOREIGN KEY(userID) REFERENCES bruker(userID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE comments  (
	commentID int(32) NOT NULL AUTO_INCREMENT, 
	userID int(32) NOT NULL, 
	postID int(32) NOT NULL, 
	timestamp int(10) NOT NULL, 
	source varchar(256) NULL,
	comment varchar(20000),
	PRIMARY KEY (commentID),
	FOREIGN KEY (userID) REFERENCES users(userID),
	FOREIGN KEY (postID) REFERENCES blogPost(postID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE pictures (
	pictureID int(32) NOT NULL AUTO_INCREMENT,
	userID int(32) NOT NULL,
	url varchar(1024) NOT NULL,
	timestamp int(10) NOT NULL,
	PRIMARY KEY (pictureID),
	FOREIGN KEY (userID) REFERENCES users(userID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE postViews (
	viewID int(255) NOT NULL AUTO_INCREMENT,
	postID int(32) NOT NULL,
	timestamp int(10) NOT NULL,
	ipAddress varchar(15) NOT NULL,
	PRIMARY KEY (viewID),
	FOREIGN KEY (postID) REFERENCES blogPost(postID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

 CREATE TABLE blogViews (
	viewID int(255) NOT NULL AUTO_INCREMENT,
	bloggID int(32) NOT NULL,
	timestamp int(10) NOT NULL,
	ipAddress varchar(15) NOT NULL,
	PRIMARY KEY (viewID),
	FOREIGN KEY (bloggID) REFERENCES blog(blogID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
