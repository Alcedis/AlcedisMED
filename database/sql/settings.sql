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

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES latin1 */;

--
-- Daten f�r Tabelle `settings`
--

INSERT INTO `settings` (`settings_id`, `software_version`, `software_title`, `software_custom_title`, `fastreg`, `fastreg_role`, `auto_patient_id`, `patient_initials_only`, `show_last_login`, `allow_registration`, `allow_password_reset`, `user_max_login`, `user_max_login_deactivated`, `pat_list_first`, `pat_list_second`, `extended_swage`, `show_pictures`, `report_debug`, `deactivate_range_check`, `fake_system_date`, `logo`, `img_type`, `check_ie`, `erkrankung_b`, `erkrankung_d`, `erkrankung_gt`, `erkrankung_h`, `erkrankung_kh`, `erkrankung_leu`, `erkrankung_lg`, `erkrankung_lu`, `erkrankung_ly`, `erkrankung_m`, `erkrankung_nt`, `erkrankung_oes`, `erkrankung_p`, `erkrankung_pa`, `erkrankung_snst`, `erkrankung_sst`, `feature_dkg_oz`, `feature_dkg_b`, `feature_dkg_d`, `feature_dkg_gt`, `feature_dkg_h`, `feature_dkg_lu`, `feature_dkg_p`, `interface_gekid`, `interface_gekid_plus`, `interface_ekr_h`, `interface_ekr_rp`, `interface_ekr_sh`, `interface_krbw`, `interface_hl7_e`, `interface_gkr`, `interface_adt`, `interface_gtds`, `interface_onkeyline`, `interface_dmp_2014`, `interface_qs181`, `interface_eusoma`, `interface_wbc`, `interface_wdc`, `interface_onkonet`, `interface_patho_e`, `interface_oncobox_darm`, `interface_oncobox_prostata`, `interface_patho_i`, `interface_kr_he`, `konferenz`, `email_attachment`, `zweitmeinung`, `rolle_konferenzteilnehmer`, `rolle_dateneingabe`, `tools`, `dokument`, `pacs`, `max_pacs_savetime`, `codepicker_top_limit`, `status_lasttime`, `historys_path`, `createuser`, `createtime`, `updateuser`, `updatetime`) VALUES
(1, '4.1.2', 'AlcedisMED', NULL, NULL, NULL, NULL, NULL, '1', '1', '1', 10, NULL, 'patient_nr', 'aufnahme_nr', 1, '1', NULL, NULL, '2014-12-24', NULL, NULL, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', NULL, NULL, '1', '1', '1', NULL, '1', '1', NULL, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', NULL, 2, 10, '2015-06-11 09:51:54', '/var/local/alcedis/med4/exports/histories', 1, '2011-02-08 07:34:58', 4, '2016-03-18 15:23:58');

--
-- Daten f�r Tabelle `settings_export`
--

INSERT INTO `settings_export` (`settings_export_id`, `name`, `settings`, `bem`, `createuser`, `createtime`, `updateuser`, `updatetime`) VALUES
(1, 'qsmed', '[{\r\n"org_id":0,\r\n"bsnr":50,\r\n"abtnr":40,\r\n"fachabt":5000,\r\n"standort":""\r\n}]', NULL, 1, '2013-01-08 12:14:15', 1, '2016-08-02 09:04:29'),
(2, 'krbw', '[{\r\n"org_id":0,\r\n"ansprechpartner_name":"Dieter Demoarzt",\r\n"ansprechpartner_email":"email",\r\n"source_system":"Alcedis MED4",\r\n"main_dir":"krbw/",\r\n"xml_dir":"xml/",\r\n"zip_dir":"zip/"\r\n}]', 'Krebsregister Baden-W�rttemberg', 1, NULL, 4, '2015-05-28 13:35:23'),
(3, 'onkeyline', '[{\r\n"org_id":0,\r\n"ansprechpartner_name":"Dieter Demoarzt",\r\n"ansprechpartner_email":"email",\r\n"source_system":"Softwarename",\r\n"main_dir":"onkeyline/",\r\n"xml_dir":"xml/",\r\n"zip_dir":"zip/"\r\n}]', 'ONkeyLINE', 1, NULL, 1, '2016-08-02 09:04:11'),
(9, 'hl7', '[{\r\n"org_id":0,\r\n"path":"hl7out",\r\n"profile":["a"],\r\n"receiving_application":"Receiver",\r\n"receiving_facility":"Firma",\r\n"export_only_after_closure": false,\r\n"documentType": {\r\n   "kp" : {\r\n       "type": "Conference Protocoll"\r\n   },\r\n   "br" : {\r\n       "type": "Name of type",\r\n       "addDisease" : true\r\n   }\r\n}\r\n}]', 'Profile: f�r mehrere gleichzeitig mit: \na - MDM (HL7 Nachricht mit Dokumentinhalt)\nb - MDM (HL7 Nachricht mit Dokumentreferenz)\nc - ORU(mit Nachrichteninhalt)', 1, '2013-04-30 14:40:05', 1, '2016-08-02 08:51:07'),
(5, 'wbc', '[{\r\n"org_id": "0",\r\n"zentrum_id": "0",\r\n"ansprechpartner_name":"Dieter Demoarzt",\r\n"ansprechpartner_email":"email",\r\n"schema_version_typ":"104",\r\n"schema_version_jahr":"2012",\r\n"sw_hersteller":"Softwarehersteller",\r\n"sw_name":"Softwarename",\r\n"main_dir":"wbc/",\r\n"xml_dir":"xml/",\r\n"zip_dir":"zip/"\r\n}]', 'WBC-Export', NULL, NULL, 1, '2016-08-02 09:06:36'),
(6, 'wdc', '[{\r\n"org_id":0,\r\n"zentrum_id":"0",\r\n"ansprechpartner_name":"Dieter Demoarzt",\r\n"ansprechpartner_email":"Email",\r\n"schema_version_typ":"105",\r\n"schema_version_jahr":"2011",\r\n"sw_hersteller":"Softwarehersteller",\r\n"sw_name":"Softwarename",\r\n"main_dir":"wdc/",\r\n"xml_dir":"xml/",\r\n"zip_dir":"zip/"\r\n}]', 'WDC-Export', NULL, NULL, 1, '2016-08-02 09:07:12'),
(15, 'dmp_2014', '[{\r\n"org_id":0,\r\n"produkt_verantwortlicher_vorname":"Vorname",\r\n"produkt_verantwortlicher_nachname":"Nachname",\r\n"sw_hersteller":"Hersteller",\r\n"sw_hersteller_strasse":"Stra�?e",\r\n"sw_hersteller_hausnr":"Hausnr",\r\n"sw_hersteller_plz":"PLZ",\r\n"sw_hersteller_ort":"Ort",\r\n"sw_hersteller_telefon":"Telefon",\r\n"sw_hersteller_telefax":"Telefax",\r\n"sw_hersteller_email_support":"support",\r\n"sw_hersteller_website":"webseite",\r\n"sw_name":"Softwarename",\r\n"kbv_pruefnummer":"kbv prfnummer",\r\n"xsd_software_version":"3.00",\r\n"xpm_software_version":"3.00",\r\n"empfaenger_ik":"xxx",\r\n"empfaenger2_bedingung":"%aok%",\r\n"empfaenger2_ik":"591102528",\r\n"main_dir":"dmp2014/",\r\n"check_dir":"check/",\r\n"xml_dir":"xml/",\r\n"zip_dir":"zip/",\r\n"zip_ok_dir":"ok/",\r\n"melder_user_id":"0"\r\n}]', NULL, 4, '2014-09-22 16:29:46', 1, '2016-08-02 08:48:39'),
(11, 'oncobox_darm', '[{\r\n"org_id":0,\r\n"zentrum_id":"999",\r\n"HauptNebenStandort":"Milchstrasse",\r\n"ansprechpartner_name":"Dieter Demoarzt",\r\n"ansprechpartner_email":"email",\r\n"schema_version":"E_1_1_1",\r\n"sw_hersteller":"Alcedis GmbH",\r\n"sw_name":"Alcedis MED",\r\n"main_dir":"oncobox_darm/",\r\n"xml_dir":"xml/",\r\n"zip_dir":"zip/"\r\n}]', 'OncoBox', NULL, NULL, 1, '2016-08-02 08:58:10'),
(12, 'gkr', '[{\r\n"org_id":0,\r\n"ansprechpartner_name":"Name",\r\n"ansprechpartner_email":"email",\r\n"source_system":"softwarename",\r\n"main_dir":"gkr/",\r\n"xml_dir":"xml/",\r\n"zip_dir":"zip/",\r\n"kennung":"ALCE",\r\n"fileSuffix":".txt"\r\n}]', NULL, 4, '2014-01-21 10:57:59', 1, '2016-08-02 08:49:15'),
(13, 'gekid_plus', '[{\r\n"org_id":0,\r\n"main_dir":"gekid_plus/",\r\n"xml_dir":"xml/",\r\n"zip_dir":"zip/"\r\n}]', NULL, 4, '2014-04-11 13:52:41', 1, '2016-08-02 08:48:50'),
(16, 'oncobox_prostata', '[{\r\n"org_id":0,\r\n"zentrum_id":"999",\r\n"HauptNebenStandort":"Milchstrasse",\r\n"ansprechpartner_name":"Dieter Demoarzt",\r\n"ansprechpartner_email":"email",\r\n"schema_version":"E_5_3_1",\r\n"sw_hersteller":"Software Hersteller",\r\n"sw_name":"Software Name",\r\n"main_dir":"oncobox_prostata/",\r\n"xml_dir":"xml/",\r\n"zip_dir":"zip/"\r\n}]', NULL, 1, '2014-10-15 13:48:32', 1, '2016-08-02 08:58:50'),
(17, 'kr_he', '[{\r\n"org_id":"3",\r\n"absender_bezeichnung":"<Alcedis MED 4.1.1",\r\n"absender_ansprechpartner":"Jens Hansen",\r\n"absender_anschrift":"Torstr. 16 35435 Wettenberg",\r\n"absender_telefon":"06414605884",\r\n"absender_email":"jch@alcedis.de",\r\n"source_system":"Alcedis MED4",\r\n"meldende_stelle": "ID0000000",\r\n"main_dir":"kr/",\r\n"xml_dir":"xml/",\r\n"zip_dir":"zip/"\r\n},\r\n{\r\n"org_id":"4",\r\n"absender_bezeichnung":"<Alcedis MED 4.1.1",\r\n"absender_ansprechpartner":"Jens Hansen",\r\n"absender_anschrift":"Torstr. 16 35435 Wettenberg",\r\n"absender_telefon":"06414605884",\r\n"absender_email":"jch@alcedis.de",\r\n"source_system":"Alcedis MED4",\r\n"meldende_stelle": "ID11111111",\r\n"main_dir":"kr/",\r\n"xml_dir":"xml/",\r\n"zip_dir":"zip/"\r\n},\r\n{\r\n"org_id":"2",\r\n"absender_bezeichnung":"<Alcedis MED 4.1.1",\r\n"absender_ansprechpartner":"Jens Hansen",\r\n"absender_anschrift":"Torstr. 16 35435 Wettenberg",\r\n"absender_telefon":"06414605884",\r\n"absender_email":"jch@alcedis.de",\r\n"source_system":"Alcedis MED4",\r\n"meldende_stelle": "ID22222222",\r\n"main_dir":"kr/",\r\n"xml_dir":"xml/",\r\n"zip_dir":"zip/"\r\n},\r\n{\r\n"org_id":"4",\r\n"absender_bezeichnung":"<Alcedis MED 4.1.1",\r\n"absender_ansprechpartner":"Jens Hansen",\r\n"absender_anschrift":"Torstr. 16 35435 Wettenberg",\r\n"absender_telefon":"06414605884",\r\n"absender_email":"jch@alcedis.de",\r\n"source_system":"Alcedis MED4",\r\n"meldende_stelle": "ID33333333",\r\n"main_dir":"kr/",\r\n"xml_dir":"xml/",\r\n"zip_dir":"zip/"\r\n}]', NULL, 1, '2015-10-16 11:53:42', 1, '2016-05-17 09:02:12');

--
-- Daten f�r Tabelle `settings_hl7`
--

INSERT INTO `settings_hl7` (`settings_hl7_id`, `bem`, `active`, `import_mode`, `valid_event_types`, `patient_ident`, `user_ident`, `cache_dir`, `max_log_time`, `max_usability_time`, `update_patient_due_caching`, `cache_diagnose_active`, `cache_diagnose_hl7`, `cache_diagnose_filter`, `cache_diagnosetyp_active`, `cache_diagnosetyp_hl7`, `cache_diagnosetyp_filter`, `cache_abteilung_active`, `cache_abteilung_hl7`, `cache_abteilung_filter`, `import_diagnose_active`, `import_diagnose_hl7`, `import_diagnose_filter`, `import_diagnosetyp_active`, `import_diagnosetyp_hl7`, `import_diagnosetyp_filter`, `createuser`, `createtime`, `updateuser`, `updatetime`) VALUES
(1, NULL, '1', 'manuell', 'ADT/A01, ADT/A02, ADT/A03, ADT/A04, ADT/A05, ADT/A06, ADT/A07, ADT/A08, ADT/A31, ADT/P01, BAR/P01, BAR/P02, BAR/P03, BAR/P04, BAR/P05', 'patientid', 'nameplz', 'hl7/', 60, 21, '1', NULL, 'DG1.3', '/C|D/', NULL, 'DG1.6', '/AD/', NULL, 'PV1.3', '/Test/', '1', 'DG1.3', '/C|D/', NULL, 'DG1.6', '/AD/', 0, '0000-00-00 00:00:00', 1, '2016-04-11 11:39:07');

--
-- Daten f�r Tabelle `settings_hl7field`
--

INSERT INTO `settings_hl7field` (`settings_hl7field_id`, `settings_hl7_id`, `med_feld`, `import`, `hl7`, `hl7_bereich`, `hl7_back`, `feld_typ`, `feld_trim_null`, `multiple`, `multiple_segment`, `multiple_filter`, `ext`, `createuser`, `createtime`, `updateuser`, `updatetime`) VALUES
(1, 1, 'arzt.fachabteilung', NULL, 'PV1.9.3', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 1, '2011-04-07 15:23:20', 1, '2013-02-01 09:29:48'),
(2, 1, 'arzt.nachname', '1', 'PV1.9.1', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 1, '2011-04-07 15:21:38', NULL, NULL),
(3, 1, 'arzt.ort', '1', 'PV1.9.6', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 1, '2011-04-07 15:23:21', NULL, NULL),
(4, 1, 'arzt.plz', '1', 'PV1.9.6', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 1, '2011-04-07 15:23:21', NULL, NULL),
(5, 1, 'arzt.titel', '1', 'PV1.9.5', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 1, '2011-04-07 15:23:20', 1, '2013-02-01 09:29:22'),
(6, 1, 'arzt.strasse', NULL, 'PV1.9.7', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 1, '2011-04-07 15:23:54', 1, '2013-02-01 09:29:54'),
(7, 1, 'arzt.vorname', '1', 'PV1.9.2', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 1, '2011-04-07 15:21:38', NULL, NULL),
(8, 1, 'aufenthalt.aufnahmedatum', '1', 'PV1.44', NULL, NULL, 'date', NULL, 'first', NULL, NULL, NULL, 1, '2011-04-07 15:27:07', NULL, NULL),
(9, 1, 'aufenthalt.aufnahmenr', '1', 'PV1.19', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 1, '2011-04-07 15:27:07', NULL, NULL),
(10, 1, 'aufenthalt.entlassungsdatum', '1', 'PV1.45', NULL, NULL, 'date', NULL, 'first', NULL, NULL, NULL, 1, '2011-04-07 15:27:07', NULL, NULL),
(11, 1, 'diagnoseCode', NULL, 'DG1.3', NULL, NULL, 'string', NULL, 'all', NULL, NULL, NULL, 1, '2011-04-05 08:50:12', NULL, NULL),
(12, 1, 'erkrankung.erkrankung', '1', 'DG1.3', NULL, NULL, 'string', NULL, 'all', NULL, NULL, NULL, 1, '2011-04-14 15:45:07', NULL, NULL),
(13, 1, 'messageId', NULL, 'MSH.8.1', NULL, NULL, '', NULL, 'first', NULL, NULL, NULL, 1, '2011-04-01 11:00:42', NULL, NULL),
(14, 1, 'messageType', NULL, 'MSH.8.0', NULL, NULL, '', NULL, 'first', NULL, NULL, NULL, 1, '2011-04-01 10:22:30', 1, '2011-04-01 10:58:21'),
(15, 1, 'patient.geburtsdatum', '1', 'PID.7', NULL, NULL, 'date', NULL, 'first', NULL, NULL, NULL, 1, '2011-04-01 10:08:16', 1, '2011-04-01 11:10:55'),
(16, 1, 'patient.geburtsname', '1', 'PID.6', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 14, '2011-08-19 10:52:59', NULL, NULL),
(17, 1, 'patient.geschlecht', '1', 'PID.8', NULL, NULL, 'string', NULL, 'first', NULL, NULL, '{"M":"m","W":"w","F":"w"}', 14, '2011-08-19 10:52:59', 1, '2011-11-08 14:48:02'),
(18, 1, 'patient.kv_einlesedatum', '1', 'IN1.29', NULL, NULL, 'date', NULL, 'first', NULL, NULL, NULL, 14, '2011-08-19 10:52:59', NULL, NULL),
(19, 1, 'patient.kv_gueltig_bis', '1', 'IN1.49.1', NULL, NULL, 'date', NULL, 'first', NULL, NULL, NULL, 14, '2011-08-19 10:52:59', NULL, NULL),
(20, 1, 'patient.kv_iknr', '1', 'IN1.3', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 14, '2011-08-19 10:52:59', NULL, NULL),
(21, 1, 'patient.kv_nr', '1', 'IN1.2', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 14, '2011-08-19 10:52:59', NULL, NULL),
(22, 1, 'patient.kv_status', '1', 'IN1.15', '1', NULL, 'string', NULL, 'first', NULL, NULL, NULL, 14, '2011-08-19 10:52:59', NULL, NULL),
(23, 1, 'patient.kv_statusergaenzung', '1', 'IN1.35.3', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 14, '2011-08-19 10:52:59', NULL, NULL),
(24, 1, 'patient.nachname', '1', 'PID.5.0', NULL, NULL, 'string', NULL, 'first', 'test', 'test', NULL, 1, '2011-03-31 09:53:13', 14, '2011-08-19 09:20:38'),
(25, 1, 'patient.ort', '1', 'PID.11.2', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 14, '2011-08-19 09:17:59', 1, '2013-02-01 09:42:33'),
(26, 1, 'patient.patient_nr', '1', 'PID.3', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 1, '2011-04-01 10:08:16', 1, '2011-04-01 11:10:45'),
(27, 1, 'patient.plz', '1', 'PID.11.4', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 14, '2011-08-19 09:17:59', 1, '2013-02-01 09:42:03'),
(28, 1, 'patient.staat', '1', 'PID.26', NULL, NULL, 'string', NULL, 'first', NULL, NULL, '{"DE":"D", "D":"D"}', 14, '2011-08-19 10:52:59', 1, '2016-02-01 14:22:29'),
(29, 1, 'patient.strasse', '1', 'PID.11.0', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 14, '2011-08-19 09:17:59', 14, '2011-08-19 10:44:49'),
(30, 1, 'patient.telefon', '1', 'PID.13', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 14, '2011-08-19 10:52:59', NULL, NULL),
(31, 1, 'patient.titel', NULL, 'PID.5.4', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 14, '2011-08-19 09:23:11', 1, '2013-02-01 17:13:20'),
(32, 1, 'patient.vorname', '1', 'PID.5.1', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 1, '2011-04-01 11:03:41', 1, '2016-02-01 14:20:57'),
(33, 1, 'diagnose.datum', '1', 'DG1.5', NULL, NULL, 'date', NULL, 'all', NULL, NULL, NULL, 1, '2012-01-10 08:11:35', 1, '2012-01-16 11:08:25'),
(34, 1, 'diagnose.diagnose', '1', 'DG1.3', NULL, NULL, 'string', NULL, 'all', NULL, NULL, NULL, 1, '2012-01-10 08:15:53', 1, '2012-01-24 13:04:13'),
(35, 1, 'diagnose.diagnose_version', '1', 'DG1.3.2', NULL, NULL, 'string', NULL, 'all', NULL, NULL, NULL, 1, '2012-01-10 08:19:08', 1, '2012-01-16 12:16:54'),
(36, 1, 'diagnose.diagnose_seite', '1', 'DG1.3', '1', '1', 'string', NULL, 'all', NULL, NULL, '{"B":"B","R":"R","L":"L","b":"B","r":"R","l":"L"}', 1, '2012-01-17 09:08:40', 1, '2012-01-26 09:42:58'),
(37, 1, 'diagnose.diagnose_text', '1', 'DG1.3.1', NULL, NULL, 'string', NULL, 'all', NULL, NULL, NULL, 1, '2012-01-17 09:31:50', 1, '2012-01-24 13:02:50'),
(38, 1, 'vorlage_krankenversicherung.name', '1', 'IN1.4', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 1, '2012-11-20 10:07:11', NULL, NULL),
(39, 1, 'vorlage_krankenversicherung.iknr', '1', 'IN1.3.0', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 1, '2012-11-20 10:07:11', 1, '2013-02-01 09:32:12'),
(40, 1, 'vorlage_krankenversicherung.strasse', '1', 'IN1.5.0', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 1, '2012-11-20 10:08:18', NULL, NULL),
(41, 1, 'vorlage_krankenversicherung.ort', '1', 'IN1.5.2', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 1, '2012-11-20 10:08:58', NULL, NULL),
(42, 1, 'vorlage_krankenversicherung.plz', '1', 'IN1.5.4', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 1, '2012-11-20 10:09:34', 1, '2012-11-20 10:12:32'),
(43, 1, 'diagnose.timestamp', '1', 'DG1.5', NULL, NULL, 'string', NULL, 'all', NULL, NULL, NULL, 1, '2013-03-26 17:31:53', NULL, NULL),
(44, 1, 'patho.observation_end_date', '1', 'ORB.7', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 1, '2013-05-08 10:08:16', 1, '2013-05-08 10:08:16'),
(45, 1, 'patho.histologie_nr', '1', 'ORB.1', NULL, NULL, 'string', NULL, 'first', NULL, NULL, NULL, 1, '2013-05-08 10:09:45', 1, '2013-05-08 10:09:45');

--
-- Daten f�r Tabelle `settings_import`
--

INSERT INTO `settings_import` (`settings_import_id`, `name`, `settings`, `bem`, `createuser`, `createtime`, `updateuser`, `updatetime`) VALUES
(1, 'patho', '[{\r\n"org_id":0,\r\n"source_dir":"/var/local/alcedis/med4/patho"\r\n}]', 'Patho', NULL, NULL, 1, '2015-12-15 09:04:05');

--
-- Daten f�r Tabelle `settings_report`
--

INSERT INTO `settings_report` (`settings_report_id`, `name`, `erkrankung`, `settings`, `bem`, `createuser`, `createtime`, `updateuser`, `updatetime`) VALUES
(1, 'DKG Brustzentrum', 'b', '{\r\n    "feature" : "dkg_b",\r\n    "b01" : {\r\n        "name" : "b01",\r\n        "type" : "xls",\r\n        "active": true\r\n    },\r\n    "b02" : {\r\n        "name" : "b02",\r\n        "type" : "rtf",\r\n        "img" : "doc",\r\n        "active": true\r\n    },\r\n    "b03" : {\r\n        "name" : "b03",\r\n        "type" : "pdf",\r\n        "img" : "pdf",\r\n        "active": true\r\n    },\r\n    "b04" : {\r\n        "name" : "b04",\r\n        "type" : "pdf",\r\n        "img" : "pdf",\r\n        "chart" : {\r\n            "pt" : ["gesamt","ptx","pt0","pta","ptis","pt1","pt2","pt3","pt4"]\r\n        },\r\n        "active": true\r\n    },\r\n    "b04_1" : {\r\n        "name" : "b04_1",\r\n        "type" : "xls",\r\n        "img" : "xls",\r\n        "active": true\r\n    },\r\n    "b05" : {\r\n        "name" : "b05",\r\n        "type" : "xls",\r\n        "img" : "xls",\r\n        "active": true\r\n    },\r\n    "b06" : {\r\n        "name" : "b06",\r\n        "type" : "xls",\r\n        "img" : "xls",\r\n        "active": true\r\n    },\r\n    "b07" : {\r\n        "name" : "b07",\r\n        "type" : "xls",\r\n        "img" : "xls",\r\n        "active": true\r\n    }\r\n}', NULL, 1, '2014-10-08 16:46:26', 1, '2014-10-09 10:40:47'),
(2, 'DKG Darmzentrum', 'd', '{"d01":{\r\n"name":"d01",\r\n"type":"xls",\r\n"active":true},\r\n"d02":{\r\n"name":"d02",\r\n"type":"rtf",\r\n"img":"doc",\r\n"active":true},\r\n"d02_2":{\r\n"name":"d02_2",\r\n"type":"rtf",\r\n"img":"doc",\r\n"active":true},\r\n"d03":{\r\n"name":"d03",\r\n"type":"pdf",\r\n"img":"pdf",\r\n"active":true},\r\n"d04":{\r\n"name":"d04",\r\n"type":"pdf",\r\n"chart":{\r\n"uicc":["gesamt",\r\n"0",\r\n"i",\r\n"ii",\r\n"iii",\r\n"iv"]},\r\n"img":"pdf",\r\n"active":true},\r\n"d04_1":{\r\n"type":"xls",\r\n"name":"d04_1",\r\n"img":"xls",\r\n"active":true},\r\n"d05_1":{\r\n"name":"d05_1",\r\n"img":"xls",\r\n"type":"phpexcel",\r\n"active":true},\r\n"d05_2":{\r\n"name":"d05_2",\r\n"img":"xls",\r\n"type":"phpexcel",\r\n"active":true},\r\n"d06":{\r\n"name":"d06",\r\n"type":"xls",\r\n"img":"xls",\r\n"active":true},\r\n"d07":{\r\n"name":"d07",\r\n"img":"xls",\r\n"type":"xls",\r\n"active":true},\r\n"feature":"dkg_d",\r\n"oncobox_darm":{\r\n"type":"feature",\r\n"img":"xml",\r\n"name":"oncobox_darm",\r\n"param":{\r\n"feature":"export",\r\n"page":"oncobox_darm",\r\n"backpage":"auswertungen"},\r\n"active":true}}', NULL, 1, '2014-10-08 16:46:45', 3, '2015-03-04 15:32:57'),
(3, 'DKG Gyn�kologisches Krebszentrum', 'gt', '{\r\n    "feature" : "dkg_gt",\r\n    "gt01_1" : {\r\n        "name" : "gt01_1",\r\n        "type" : "xls",\r\n        "active" : true\r\n    },\r\n    "gt01_2" : {\r\n        "name" : "gt01_2",\r\n        "type" : "xls",\r\n        "active" : true\r\n    },\r\n    "gt02" : {\r\n        "name" : "gt02",\r\n        "type" : "rtf",\r\n        "img" : "doc",\r\n        "active" : true\r\n    },\r\n    "gt03" : {\r\n        "name" : "gt03",\r\n        "img" : "pdf",\r\n        "type" : "pdf",\r\n        "active" : true\r\n    },\r\n    "gt04" : {\r\n        "name" : "gt04",\r\n        "type" : "pdf",\r\n        "img" : "pdf",\r\n        "chart" : {\r\n            "figo" : ["gesamt","0","i","ii","iii","iv"]\r\n        },\r\n        "active" : true\r\n    },\r\n    "gt04_1" : {\r\n        "name" : "gt04_1",\r\n        "type" : "xls",\r\n        "img" : "xls",\r\n        "active" : true\r\n    },\r\n    "gt05" : {\r\n        "name" : "gt05",\r\n        "type" : "phpexcel",\r\n        "img" : "xls",\r\n        "active" : true\r\n    },\r\n    "gt06_1" : {\r\n        "name" : "gt06_1",\r\n        "type" : "xls",\r\n        "img" : "xls",\r\n        "active" : true\r\n    },\r\n    "gt07" : {\r\n        "name" : "gt07",\r\n        "type" : "phpexcel",\r\n        "img" : "xls",\r\n        "active" : true\r\n    }\r\n}', NULL, 1, '2014-10-08 16:46:58', 1, '2014-10-09 11:05:10'),
(4, 'Pankreas', 'pa', '{  \r\n    "feature" : ["dkg_oz", "dkg_d"],\r\n    "pa01_1" : {\r\n        "name" : "pa01_1",\r\n        "type" : "xls",\r\n        "active" : true\r\n    },\r\n    "pa01_2" : {\r\n        "name" : "pa01_2",\r\n        "type" : "xls",\r\n        "active" : true\r\n        },\r\n    "pa02" : {\r\n        "name" : "pa02",\r\n        "img" : "doc",\r\n        "type" : "rtf",\r\n        "active" : true\r\n    },\r\n    "pa04" : {\r\n        "name" : "pa04",\r\n        "type" : "pdf",\r\n        "img" : "pdf",\r\n        "chart" : {\r\n            "uicc" : ["gesamt","0","i","ii","iii","iv"]\r\n        },\r\n        "active" : true\r\n    },\r\n    "pa04_1" : {\r\n        "name" : "pa04_1",\r\n        "type" : "xls",\r\n        "img" : "xls",\r\n        "active" : true\r\n    },\r\n    "pa05" : {\r\n        "name" : "pa05",\r\n        "type" : "phpexcel",\r\n        "img" : "xls",\r\n        "active" : true\r\n    },\r\n    "pa06" : {\r\n        "name" : "pa06",\r\n        "type" : "xls",\r\n        "img" : "xls",\r\n        "active" : true\r\n    },\r\n    "pa07" : {\r\n        "name" : "pa07",\r\n        "type" : "xls",\r\n        "img" : "xls",\r\n        "active" : true\r\n    }\r\n}', NULL, NULL, NULL, 1, '2014-10-09 10:38:43'),
(5, 'DKG Hauttumorzentrum', 'h', '{   \r\n    "feature" : "dkg_h",\r\n    "h01_1" : {\r\n        "name" : "h01_1",\r\n        "type" : "xls",\r\n        "active" : true\r\n    },\r\n    "h01_2" : {\r\n        "name" : "h01_2",\r\n        "type" : "xls",\r\n        "active" : true\r\n    },\r\n    "h02" : {\r\n        "name" : "h02",\r\n        "img" : "xls",\r\n        "type" : "phpexcel",\r\n        "active" : true\r\n    },\r\n    "h03" : {\r\n        "name" : "h03",\r\n        "type" : "pdf",\r\n        "img" : "pdf",\r\n        "active" : true\r\n    },\r\n    "h04" : {\r\n        "name" : "h04",\r\n        "type" : "pdf",\r\n        "img" : "pdf",\r\n        "chart" : {\r\n            "pt" : ["gesamt","ptx","pt0","pta","ptis","pt1","pt2","pt3","pt4"]\r\n        },\r\n        "active" : true\r\n    },\r\n    "h04_1" : {\r\n        "name" : "h04_1",\r\n        "img" : "xls",\r\n        "type" : "xls",\r\n        "active" : true\r\n    },\r\n    "h05" : {\r\n        "name" : "h05",\r\n        "img" : "xls",\r\n        "type" : "phpexcel",\r\n        "active" : true\r\n    },\r\n    "h06_1" : {\r\n        "name" : "h06_1",\r\n        "img" : "xls",\r\n        "type" : "xls",\r\n        "active" : true\r\n    },\r\n    "h07" : {\r\n        "name" : "h07",\r\n        "img" : "xls",\r\n        "type" : "xls",\r\n        "active" : true\r\n    }\r\n}', NULL, NULL, NULL, 1, '2014-10-09 10:37:55'),
(6, 'DKG Lungenkrebszentrum', 'lu', '{    \r\n    "feature" : "dkg_lu",\r\n    "lu01_1" : {\r\n        "name" : "lu01_1",\r\n        "type" : "xls",\r\n        "active" : true\r\n    },\r\n    "lu02" : {\r\n        "name" : "lu02",\r\n        "type" : "rtf",\r\n        "img" : "doc",\r\n        "active" : true\r\n    },\r\n    "lu03" : {\r\n        "name" : "lu03",\r\n        "type" : "pdf",\r\n        "img" : "pdf",\r\n        "active" : true\r\n    },\r\n    "lu04" : {\r\n        "name" : "lu04",\r\n        "type" : "pdf",\r\n        "chart" : {\r\n            "pt" : ["gesamt","ptx","pt0","pta","ptis","pt1","pt2","pt3","pt4"]\r\n        },\r\n        "img" : "pdf",\r\n        "active" : true\r\n    },\r\n    "lu04_1" : {\r\n        "name" : "lu04_1",\r\n        "img" : "xls",\r\n        "type" : "xls",\r\n        "active" : true\r\n    },\r\n    "lu05" : {\r\n        "name" : "lu05",\r\n        "img" : "xls",\r\n        "type" : "phpexcel",\r\n        "active" : true\r\n    },\r\n    "lu06_1" : {\r\n        "name" : "lu06_1",\r\n        "img" : "xls",\r\n        "type" : "xls",\r\n        "active" : true\r\n    },\r\n    "lu07" : {\r\n        "name" : "lu07",\r\n        "img" : "xls",\r\n        "type" : "xls",\r\n        "active" : true\r\n    }\r\n}', NULL, NULL, NULL, 1, '2014-10-09 10:38:03'),
(7, 'DKG Onkologisches Zentrum', 'oz', '{\r\n    "feature" : "dkg_oz",\r\n    "oz01" : {\r\n        "name" : "oz01",\r\n        "type" : "xls",\r\n        "active" : true\r\n    },\r\n    "oz02_1" : {\r\n        "name" : "oz02_1",\r\n        "img" : "doc",\r\n        "type" : "rtf",\r\n        "active" : true\r\n    },\r\n    "oz02_2" : {\r\n        "name" : "oz02_2",\r\n        "img" : "doc",\r\n        "type" : "rtf",\r\n        "active" : true\r\n    },\r\n    "oz03" : {\r\n        "name" : "oz03",\r\n        "img" : "pdf",\r\n        "type" : "pdf",\r\n        "active" : true\r\n    },\r\n    "oz04" : {\r\n        "name" : "oz04",\r\n        "img" : "pdf",\r\n        "chart" : {\r\n            "pt" : ["gesamt", "ptx", "pt0", "pta", "ptis", "pt1", "pt2", "pt3", "pt4"]\r\n        },\r\n        "type" : "pdf",\r\n        "active" : true\r\n    },\r\n    "oz04_1" : {\r\n        "name" : "oz04_1",\r\n        "img" : "xls",\r\n        "type" : "xls",\r\n        "active" : true\r\n    }\r\n}', NULL, NULL, NULL, 1, '2014-10-09 10:38:11'),
(8, 'DKG Prostatakarzinomzentrum', 'p', '{"feature":"dkg_p","oncobox_prostata":{"type":"feature","img":"xml","name":"oncobox_prostata","param":{"feature":"export","page":"oncobox_prostata","backpage":"auswertungen"},"active":true},"p01":{"name":"p01","type":"xls","active":true},"p01_2":{"name":"p01_2","type":"xls","active":true},"p01_3":{"name":"p01_3","type":"xls","active":true},"p02":{"name":"p02","type":"rtf","img":"doc","active":true},"p03":{"name":"p03","type":"pdf","img":"pdf","active":true},"p04":{"name":"p04","type":"pdf","img":"pdf","chart":{"pt":["gesamt","ptx","pt0","pta","ptis","pt1","pt2","pt3","pt4"],"uicc":["gesamt","0","i","ii","iii","iv"]},"active":true},"p04_1":{"name":"p04_1","img":"xls","type":"xls","active":true},"p05_1":{"name":"p05_1","img":"doc","type":"rtf","active":true},"p05_2":{"name":"p05_2","img":"doc","type":"rtf","active":true},"p06":{"name":"p06","type":"xls","img":"xls","active":true},"p07":{"name":"p07","img":"xls","type":"phpexcel","active":true}}', NULL, NULL, NULL, 3, '2015-11-03 11:19:07'),
(9, 'Kopf-Hals-Tumor', 'kh', '{  \r\n    "feature" : ["dkg_oz"],\r\n    "kh01" : {\r\n        "name" : "kh01",\r\n        "type" : "xls",\r\n        "active" : true\r\n    },\r\n    "kh02" : {\r\n        "name" : "kh02",\r\n        "type" : "rtf",\r\n        "img" : "doc",\r\n        "active" : true\r\n    },\r\n    "kh04" : {\r\n        "name" : "kh04",\r\n        "type" : "pdf",\r\n        "img" : "pdf",\r\n        "chart" : {\r\n            "pt" : ["gesamt","ptis","pt0","pt1","pt2","pt3","pt4"],\r\n            "uicc" : ["gesamt","0","i","ii","iii","iv"]\r\n        },\r\n        "active" : true\r\n    },\r\n    "kh04_1" : {\r\n        "name" : "kh04_1",\r\n        "img" : "xls",\r\n        "type" : "xls",\r\n        "active" : true\r\n    },\r\n    "kh05" : {\r\n        "name" : "kh05",\r\n        "type" : "phpexcel",\r\n        "img" : "xls",\r\n        "active": true\r\n    },\r\n   "kh06" : {\r\n        "name" : "kh06",\r\n        "type" : "xls",\r\n        "img" : "xls",\r\n        "active" : true\r\n    },\r\n    "kh07" : {\r\n        "name" : "kh07",\r\n        "img" : "xls",\r\n        "type" : "phpexcel",\r\n        "active" : true\r\n    }\r\n}', NULL, NULL, NULL, 1, '2015-03-16 10:06:28');


INSERT INTO `user` SET nachname = 'Alcedis', vorname = 'Benutzer', createtime = NOW(), createuser = 1, org = 'Organisation', strasse = 'Stra�e', hausnr = 3, plz = '35394', ort = 'Gie�en', telefon = '', telefax = '', email = '', loginname = 'admin', pwd = '5452eea2e1ff9cefa25f5fb590386dfb', pwd_change = 1;

INSERT INTO `org` SET name = 'Mandant Nr. 1', mandant = 1, createtime = NOW(), createuser = 1;

INSERT INTO `recht` SET org_id = 1, user_id = 1, rolle = 'admin', createtime = NOW(), createuser = 1;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
