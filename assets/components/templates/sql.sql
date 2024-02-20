CREATE TABLE `barangay`(
  `id` INT(6) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `name` VARCHAR(255) DEFAULT NULL,
  `deletedflag` int(1) DEFAULT 0,
  `updated_by` int(6) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `created_by` int(6) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
)
ALTER TABLE
  `evacuee`
ADD
  `barangay` INT(6);

ALTER TABLE
  `evac_center`
ADD
  `barangay` INT(6);

ALTER TABLE
  `evac_center`
ADD
  `barangay` INT(6);