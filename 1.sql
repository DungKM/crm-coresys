ALTER TABLE `activities` 
ADD COLUMN `is_pinned` TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_done`,
ADD COLUMN `is_starred` TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_pinned`;