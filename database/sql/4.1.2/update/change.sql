/*
AlcedisMED

Copyright (C) 2010-2016  Alcedis GmbH

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

ALTER TABLE `patient` ADD `land` VARCHAR(25) NULL AFTER `ort`;
ALTER TABLE `_patient` ADD `land` VARCHAR(25) NULL AFTER `ort`;

ALTER TABLE `export_case_log` ADD `hash` VARCHAR(255) NULL AFTER `anlass`;

ALTER TABLE `export_case_log` 
DROP INDEX `idx2`, 
ADD INDEX `idx2` (`erkrankung_id`, `diagnose_seite`, `anlass`) USING BTREE;

ALTER TABLE `export_history` DROP INDEX `idx1`, ADD UNIQUE `ukey1` (`export_log_id`) USING BTREE;