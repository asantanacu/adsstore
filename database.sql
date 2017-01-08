/*create database schema*/
/*CREATE SCHEMA IF NOT EXISTS ads_store DEFAULT CHARACTER SET latin1;

USE ads_store;*/

/*drop tables*/
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS images_keywords;
DROP TABLE IF EXISTS images;
DROP TABLE IF EXISTS keywords;

/*create table users*/
CREATE TABLE users (
  id INT NOT NULL AUTO_INCREMENT,
  email VARCHAR(80) NOT NULL,
  firstname VARCHAR(80) NOT NULL,
  lastname VARCHAR(80) NOT NULL,
  birth_date DATE NOT NULL,  
  password CHAR(250) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX (email)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

/*create table images*/
CREATE TABLE images (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  url VARCHAR(255) NOT NULL,
  width INTEGER NOT NULL,
  height INTEGER NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

/*create table keywords*/
CREATE TABLE keywords (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(30) NOT NULL UNIQUE,
  PRIMARY KEY (id),
  INDEX (name)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

/*create table images_keywords*/
CREATE TABLE images_keywords
(
    image_id INT NOT NULL,  
    keyword_id INT NOT NULL,  
    PRIMARY KEY (image_id, keyword_id),  
    FOREIGN KEY (image_id) REFERENCES images(id) ON DELETE CASCADE,  
    FOREIGN KEY (keyword_id) REFERENCES keywords(id) ON DELETE CASCADE
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;