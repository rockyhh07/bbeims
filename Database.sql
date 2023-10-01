

CREATE DATABASE IF NOT EXISTS `bbeims_db`;

USE `bbeims_db`;

CREATE TABLE `users` (
    `id` INT(6) PRIMARY KEY AUTO_INCREMENT,
    `username` VARCHAR(50),
    `fullname` VARCHAR(50),
    `password` VARCHAR(255),
    `designation` VARCHAR(50),
    `contact` VARCHAR(15),
    `category` VARCHAR(1),
    `active` INT(1) DEFAULT 1,
    `deletedflag` INT(1) DEFAULT 0
);

INSERT INTO `users`
SET
`username` = 'ADMIN',
`fullname` = 'ADMIN',
`password` = PASSWORD('123'),
`designation` = 'Designation 1',
`contact` = '09999999999',
`category` = 'A';


