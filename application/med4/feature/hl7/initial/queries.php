<?php

/*
 * AlcedisMED
 * Copyright (C) 2010-2016  Alcedis GmbH
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */ 

$queryRechtErkrankung = isset($_SESSION['sess_recht_erkrankung']) ? implode("','", $_SESSION['sess_recht_erkrankung']) : '';

$querys['hl7_diagnose'] = "
   SELECT
      p.hl7_diagnose_id,
      p.patient_id,
      p.org_id,
      p.vorname,
      p.nachname,
      p.patient_nr,
      p.geburtsdatum,
      p.datum,
      p.diagnosen,
      p.erkrankungen,
      p.erkrankungenbez
   FROM (
      SELECT
         MAX(d.hl7_diagnose_id) AS hl7_diagnose_id,
         p.patient_id,
         p.org_id,
         p.vorname,
         p.nachname,
         p.patient_nr,
         d.datum,
         p.geburtsdatum,
         GROUP_CONCAT(DISTINCT CONCAT_WS(' ',d.diagnose, IF(d.diagnose_seite = '-', NULL, d.diagnose_seite)) SEPARATOR ', ')   AS 'diagnosen',
         GROUP_CONCAT(DISTINCT e.erkrankung_id)             AS 'erkrankungen',
         p.erkrankungen                                     AS 'erkrankungenbez'
      FROM patient p
         INNER JOIN hl7_diagnose d ON d.patient_id = p.patient_id
         INNER JOIN erkrankung e ON p.patient_id = e.patient_id
      GROUP BY
         p.patient_id, d.datum
      ORDER BY NULL
   ) p
";

$querys['hl7_diagnose_erkrankung'] = "
   SELECT
      e.erkrankung_id,
      l.bez
   FROM erkrankung e
      LEFT JOIN l_basic l ON l.klasse = IF(e.erkrankung_detail IS NOT NULL, 'erkrankung_sst_detail', 'erkrankung') AND
                             l.code = IF(e.erkrankung_detail IS NOT NULL, e.erkrankung_detail, e.erkrankung)
";

$querys['hl7_import'] = "
   SELECT
      hl7_cache_id AS id,
      nachname,
      vorname,
      geburtsdatum,
      patient_nr,
      aufnahme_nr,
      IF(erkrankung IN('" . $queryRechtErkrankung . "'), MIN(erkrankung), '') AS erkrankung
   FROM hl7_cache
";

$querys['hl7_log_cache'] = "
   SELECT
      hl7_log_id,
      nachname,
      vorname,
      geburtsdatum,
      patient_nr,
      logtime,
      aufnahme_nr,
      status
   FROM hl7_log_cache
";

?>