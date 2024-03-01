-- ALTER TABLE
--   `evac_center`
-- ADD
--   `capacity` INT(7)
-- AFTER
--   `barangay_id`;

-- ALTER TABLE
--   `incident_archive`
-- ADD
--   `rescuer` VARCHAR(255)
-- AFTER
--   `id`;

-- UPDATE
--   `evac_center`
-- SET
--   `capacity` = 100;

-- ALTER TABLE
--   `users`
-- ADD
--   `protected` BOOLEAN DEFAULT 0
-- AFTER
--   `active`;

-- UPDATE
--   `users`
-- SET
--   `protected` = 1;


-- ALTER TABLE
--   `evacuee`
-- ADD
--   `rescuer` VARCHAR(255)
-- AFTER
--   `representative`;