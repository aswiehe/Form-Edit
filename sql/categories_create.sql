CREATE TABLE `categories` (
  `categoryid` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  PRIMARY KEY (`categoryid`));

INSERT INTO `categories` (`name`) VALUES ('Technology');
INSERT INTO `categories` (`name`) VALUES ('English');
INSERT INTO `categories` (`name`) VALUES ('Science');

CREATE TABLE `book_categories` (
  `isbn` VARCHAR(45) NOT NULL,
  `categoryid` INT NULL
);

