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

UPDATE `settings` SET `software_version` = '4.1.3' WHERE `settings`.`settings_id` = 1 LIMIT 1;

INSERT INTO `l_basic`
    (`klasse`, `code`, `bez`, `pos`, `kennung`, `code_adt`, `code_dmp`, `code_dmp_2014`, `code_eusoma`, `code_gekid`, `code_gekid_plus`, `code_gkr`, `code_gtds`, `code_krbw`, `code_kr_he`, `code_kr_hh`, `code_kr_sh`, `code_onkeyline`, `code_onkonet`, `code_qsmed`, `code_wbc`, `code_wdc`)
VALUES
    ('wirkstoff', 'dasatinib', 'Dasatinib', '525', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('status_list', '0', 'Grau (Exportiert)', '0', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL), 
	('status_list', '1', 'Rot (Fehler)', '1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('status_list', '2', 'Gelb (Warnung)', '2', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('status_list', '4', 'Grün (Vollständig)', '4', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)
;