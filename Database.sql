CREATE DATABASE IF NOT EXISTS `bbeims_db`;

USE `bbeims_db`;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(6) PRIMARY KEY AUTO_INCREMENT,
    `username` VARCHAR(50),
    `fullname` VARCHAR(50),
    `password` VARCHAR(255),
    `designation` VARCHAR(50),
    `contact` VARCHAR(15),
    `category` VARCHAR(1),
    `active` INT(1) DEFAULT 1,
    `deletedflag` INT(1) DEFAULT 0,
    `updated_by` INT(6) DEFAULT NULL,
    `updated_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`)
);

INSERT INTO `users`
SET
`username` = 'ADMIN',
`fullname` = 'ADMIN',
`password` = PASSWORD('123'),
`designation` = 'Designation 1',
`contact` = '09999999999',
`category` = 'A';

CREATE TABLE IF NOT EXISTS `evac_center` (
    `id` INT(6) PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(50),
    `address` VARCHAR(255),
    `contact` VARCHAR(15),
    `deletedflag` INT(1) DEFAULT 0,
    `updated_by` INT(6) DEFAULT NULL,
    `updated_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`)
);

INSERT INTO `evac_center`
(`name`, `address`, `contact`)
VALUES
('Evacuation Center 1','Pasig','09765468621'),
('Evacuation Center 2','Makati','09767733621'),
('Evacuation Center 3','Madaluyong','09765461155');

CREATE TABLE IF NOT EXISTS `calamity` (
    `id` INT(6) PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(50),
    `deletedflag` INT(1) DEFAULT 0,
    `updated_by` INT(6) DEFAULT NULL,
    `updated_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`)
);

INSERT INTO `calamity`
(`name`)
VALUES
('Earth Quake'),
('Flood'),
('Typhoon');

CREATE TABLE IF NOT EXISTS `evacuee` (
    `id` INT(6) PRIMARY KEY AUTO_INCREMENT,
    `lname` VARCHAR(50),
    `fname` VARCHAR(50),
    `mname` VARCHAR(50),
    `contact` VARCHAR(15),
    `age` INT(3),
    `gender` VARCHAR(1) DEFAULT 'N',
    `civil_status` VARCHAR(20) DEFAULT 'SINGLE',
    `address` VARCHAR(255),
    `head_of_the_family` VARCHAR(50),
    `evac_id` INT(6),
    `deletedflag` INT(1) DEFAULT 0,
    `updated_by` INT(6) DEFAULT NULL,
    `updated_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`),
    FOREIGN KEY (`evac_id`) REFERENCES `evac_center`(`id`)
);

INSERT INTO `evacuee` 
(`lname`,`fname`,`mname`,`contact`,`age`,`gender`,`civil_status`,`address`,`head_of_the_family`,`evac_id`)
VALUES
('ADMIN LASTNAME','ADMIN FIRSTNAME','ADMIN MIDDLENAME','09765468621','20','M','SINGLE','Mandaluyong','Ikaw','1');





