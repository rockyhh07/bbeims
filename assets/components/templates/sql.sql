CREATE TABLE `barangay`(
  `id` INT(6) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `name` VARCHAR(255) DEFAULT NULL,
  `deletedflag` int(1) DEFAULT 0,
  `updated_by` int(6) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `created_by` int(6) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
);

INSERT INTO
  `barangay`(`name`, `created_by`)
VALUES
('BARANGKA IBABA', '1');

ALTER TABLE
  `evacuee`
ADD
  `barangay_id` INT(6)
AFTER
  `representative`;

ALTER TABLE
  `evac_center`
ADD
  `barangay_id` INT(6)
AFTER
  `contact`;

ALTER TABLE
  `incident_archive`
ADD
  `barangay_id` INT(6)
AFTER
  `evac_id`;

ALTER TABLE
  `users`
ADD
  `barangay_id` INT(6)
AFTER
  `active`;

ALTER TABLE
  `evacuee`
ADD
  CONSTRAINT `evacuee_ibfk_6` FOREIGN KEY (`barangay_id`) REFERENCES `barangay` (`id`);

ALTER TABLE
  `evac_center`
ADD
  CONSTRAINT `evac_center_ibfk_3` FOREIGN KEY (`barangay_id`) REFERENCES `barangay` (`id`);

ALTER TABLE
  `incident_archive`
ADD
  CONSTRAINT `incident_archive_ibfk_4` FOREIGN KEY (`barangay_id`) REFERENCES `barangay` (`id`);

ALTER TABLE
  `users`
ADD
  CONSTRAINT `users_ibfk_3` FOREIGN KEY (`barangay_id`) REFERENCES `barangay` (`id`);

UPDATE
  `evacuee`
SET
  `barangay_id` = '1';

UPDATE
  `evac_center`
SET
  `barangay_id` = '1';

UPDATE
  `incident_archive`
SET
  `barangay_id` = '1';

UPDATE
  `users`
SET
  `barangay_id` = '1';