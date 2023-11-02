DROP DATABASE IF EXISTS `bbeims_db`;

CREATE DATABASE IF NOT EXISTS `bbeims_db`;

USE `bbeims_db`;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(6) PRIMARY KEY AUTO_INCREMENT,
    `username` VARCHAR(50),
    `fullname` VARCHAR(50),
    `password` VARCHAR(255),
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

CREATE TABLE IF NOT EXISTS `incident` (
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

CREATE TABLE IF NOT EXISTS `evacuee` (
    `id` INT(6) PRIMARY KEY AUTO_INCREMENT,
    `lname` VARCHAR(50),
    `fname` VARCHAR(50),
    `mname` VARCHAR(50),
    `contact` VARCHAR(15),
    `birthday` DATE DEFAULT NULL,
    `gender` VARCHAR(1) DEFAULT 'N',
    `civil_status` VARCHAR(20) DEFAULT 'SINGLE',
    `address` VARCHAR(255),
    `representative` INT(6) DEFAULT NULL,
    `evac_id` INT(6) DEFAULT NULL,
    `incident_id` INT(6) DEFAULT NULL,
    `incident_date` DATETIME DEFAULT NULL,
    `deletedflag` INT(1) DEFAULT 0,
    `updated_by` INT(6) DEFAULT NULL,
    `updated_date` DATETIME NULL,
    `created_by` INT(6) DEFAULT NULL,
    `created_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`representative`) REFERENCES `evacuee`(`id`),
    FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`),
    FOREIGN KEY (`evac_id`) REFERENCES `evac_center`(`id`),
    FOREIGN KEY (`incident_id`) REFERENCES `incident`(`id`)
);


INSERT INTO `users`
SET
`username` = 'ADMIN',
`fullname` = 'ADMIN',
`password` = PASSWORD('123'),
`contact` = '09999999999',
`category` = 'A',
`created_by` = '1';

INSERT INTO `users`
SET
`username` = 'angel',
`fullname` = 'ANGEL AZARRAGA',
`password` = PASSWORD('123'),
`contact` = '09999999999',
`category` = 'A',
`created_by` = '1';

INSERT INTO `users`
SET
`username` = 'staff',
`fullname` = 'STAFF',
`password` = PASSWORD('123'),
`contact` = '09999999999',
`category` = 'S',
`created_by` = '1';

INSERT INTO `evac_center`
(`name`, `address`, `contact`,  `created_by`)
VALUES
('EVACUATION CENTER 1','PASIG','09765468621', '1'),
('EVACUATION CENTER 2','MAKATI','09767733621', '1'),
('EVACUATION CENTER 3','MANDALUYONG','09765461155', '1');

INSERT INTO `incident`
(`name`, `created_by`)
VALUES
('EARTH QUAKE', '1'),
('FLOOD', '1'),
('TYPHOON', '1');

INSERT INTO `evacuee` 
(`lname`,`fname`,`mname`,`contact`,`birthday`,`gender`,`civil_status`,`address`,`representative`, `created_by`)
VALUES
('ADMIN','','','09765468621',NULL,'M','SINGLE','Mandaluyong','1', '1'),
('AZARRAGA','GEMMA','DELGADO','09123456789',NULL,'F','MARRIED','949 MMR Track St., Brgy. Barangka Ibaba Mandaluyong City','2','1'),
('MAGALINO','BEBOT','S.','09123456789',NULL,'F','SINGLE','949 MMR Track St., Brgy. Barangka Ibaba Mandaluyong City','3','1'),
('MEKUTO','FUKIKO','','09123456789',NULL,'F','SINGLE','949 MMR Track St., Brgy. Barangka Ibaba Mandaluyong City','4','1'),
('MALONE','HOE','','09123456789',NULL,'F','SINGLE','949 MMR Track St., Brgy. Barangka Ibaba Mandaluyong City','5','1'),
('WANG','MALOU','','09123456789',NULL,'F','SINGLE','949 MMR Track St., Brgy. Barangka Ibaba Mandaluyong City','6','1'),
('AZARRAGA','ANGEL','DELGADO','09123456789',NULL,'F','WIDOWED','949 MMR Track St., Brgy. Barangka Ibaba Mandaluyong City','2','1'),
('AZARRAGA','ANDREA','DELGADO','09123456789',NULL,'F','SINGLE','949 MMR Track St., Brgy. Barangka Ibaba Mandaluyong City','2','1'),
('AZARRAGA','LUCIA','DELGADO','09123456789',NULL,'F','WIDOWED','949 MMR Track St., Brgy. Barangka Ibaba Mandaluyong City','2','1');