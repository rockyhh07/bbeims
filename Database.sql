DROP DATABASE IF EXISTS `bbeims_db`;

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
    `updated_date` DATETIME NULL,
    `created_by` INT(6) DEFAULT NULL,
    `created_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
);

INSERT INTO `users`
SET
`username` = 'ADMIN',
`fullname` = 'ADMIN',
`password` = PASSWORD('123'),
`designation` = 'Designation 1',
`contact` = '09999999999',
`category` = 'A',
`created_by` = '1';

CREATE TABLE IF NOT EXISTS `evac_center` (
    `id` INT(6) PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(50) DEFAULT NULL,
    `address` VARCHAR(255) DEFAULT NULL,
    `contact` VARCHAR(15) DEFAULT NULL,
    `deletedflag` INT(1) DEFAULT 0,
    `updated_by` INT(6) DEFAULT NULL,
    `updated_date` DATETIME NULL,
    `created_by` INT(6) DEFAULT NULL,
    `created_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
);

INSERT INTO `evac_center`
(`name`, `address`, `contact`,  `created_by`)
VALUES
('EVACUATION CENTER 1','PASIG','09765468621', '1'),
('EVACUATION CENTER 2','MAKATI','09767733621', '1'),
('EVACUATION CENTER 3','MANDALUYONG','09765461155', '1');

CREATE TABLE IF NOT EXISTS `calamity` (
    `id` INT(6) PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(50) DEFAULT NULL,
    `deletedflag` INT(1) DEFAULT 0,
    `updated_by` INT(6) DEFAULT NULL,
    `updated_date` DATETIME NULL,
    `created_by` INT(6) DEFAULT NULL,
    `created_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
);

INSERT INTO `calamity`
(`name`, `created_by`)
VALUES
('EARTH QUAKE', '1'),
('FLOOD', '1'),
('TYPHOON', '1');

CREATE TABLE IF NOT EXISTS `evacuee` (
    `id` INT(6) PRIMARY KEY AUTO_INCREMENT,
    `lname` VARCHAR(50),
    `fname` VARCHAR(50),
    `mname` VARCHAR(50),
    `contact` VARCHAR(15),
    `age` INT(3) DEFAULT NULL,
    `gender` VARCHAR(1) DEFAULT 'N',
    `civil_status` VARCHAR(20) DEFAULT 'SINGLE',
    `address` VARCHAR(255),
    `head_of_the_family` INT(6) DEFAULT NULL,
    `evac_id` INT(6) DEFAULT NULL,
    `calam_id` INT(6) DEFAULT NULL,
    `deletedflag` INT(1) DEFAULT 0,
    `updated_by` INT(6) DEFAULT NULL,
    `updated_date` DATETIME NULL,
    `created_by` INT(6) DEFAULT NULL,
    `created_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`head_of_the_family`) REFERENCES `evacuee`(`id`),
    FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`),
    FOREIGN KEY (`evac_id`) REFERENCES `evac_center`(`id`),
    FOREIGN KEY (`calam_id`) REFERENCES `calamity`(`id`)
);

INSERT INTO `evacuee` 
(`lname`,`fname`,`mname`,`contact`,`age`,`gender`,`civil_status`,`address`,`head_of_the_family`, `evac_id`, `calam_id`, `created_by`)
VALUES
('ADMIN','','','09765468621','20','M','SINGLE','Mandaluyong','1', '1', '1', '1'),
('AZARRAGA','GEMMA','DELGADO','09123456789','47','F','MARRIED','949 MMR Track St., Brgy. Barangka Ibaba Mandaluyong City','2', '2', '2', '1'),
('MAGALINO','BEBOT','S.','09123456789','20','F','SINGLE','949 MMR Track St., Brgy. Barangka Ibaba Mandaluyong City','3', '3', '3', '1'),
('MEKUTO','FUKIKO','','09123456789','20','F','SINGLE','949 MMR Track St., Brgy. Barangka Ibaba Mandaluyong City','4', '1', '1', '1'),
('MALONE','HOE','','09123456789','20','F','SINGLE','949 MMR Track St., Brgy. Barangka Ibaba Mandaluyong City','5', '2', '2', '1'),
('WANG','MALOU','','09123456789','20','F','SINGLE','949 MMR Track St., Brgy. Barangka Ibaba Mandaluyong City','6', '1', '1', '1'),
('AZARRAGA','ANGEL','DELGADO','09123456789','22','F','WIDOWED','949 MMR Track St., Brgy. Barangka Ibaba Mandaluyong City','2', '2', '1', '1'),
('AZARRAGA','ANDREA','DELGADO','09123456789','19','F','SINGLE','949 MMR Track St., Brgy. Barangka Ibaba Mandaluyong City','2', '2', '1', '1'),
('AZARRAGA','LUCIA','DELGADO','09123456789','83','F','WIDOWED','949 MMR Track St., Brgy. Barangka Ibaba Mandaluyong City','2', '2', '1', '1');