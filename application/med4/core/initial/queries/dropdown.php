<?php
   /**
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

    * INFO:
    * Die Klasse dataCollector included alle query Dateien!
    * Wenn hier Variablen genutzt werden, muss sichergestellt werden,
    * dass diese auch in der Klasse vorbelegt werden!!!
    *
    * Gilt auf für die Top10 generierung der Codepicker!!!
    */

//, 'preselect' => 'user.user_id'
$querys['query_user'] = "
   SELECT
      DISTINCT user.user_id,
      CONCAT_WS(', ', user.nachname, user.vorname)
   FROM user
      INNER JOIN l_basic fa    ON fa.klasse='fachabteilung' AND fa.code=user.fachabteilung
   WHERE user.user_id NOT IN (" . ADMIN . ") AND user.inaktiv IS NULL
   ORDER BY user.nachname ASC, user.vorname ASC
";

//, 'preselect' => 'org_id'
$querys['query_org'] = "
   SELECT
      org_id,
      CONCAT_WS(', ', ort, name)
   FROM org
   WHERE inaktiv IS NULL
   ORDER BY
    ort, name
";

//, 'preselect' => 'v.vorlage_fragebogen_id'
$querys['query_vorlage_fragebogen'] = "
   SELECT
      v.vorlage_fragebogen_id,
      CONCAT_WS('', v.bez, ' (', art.bez, ')')  AS bez
   FROM vorlage_fragebogen v
      LEFT JOIN l_basic art       ON art.klasse='fragebogen_art' AND art.code=v.art
   WHERE
      v.freigabe IS NOT NULL AND v.inaktiv IS NULL
";

//, 'preselect' => 'vorlage_studie_id'
$querys['query_vorlage_studie'] = "
   SELECT
      vorlage_studie_id,
      bez
   FROM vorlage_studie
   WHERE
      (erkrankung = '$erkrankung' OR erkrankung = 'all') AND freigabe IS NOT NULL AND inaktiv IS NULL
";

//, 'preselect' => 'vorlage_labor_id'
$querys['query_vorlage_labor'] = "
   SELECT
      vorlage_labor_id,
      bez
   FROM vorlage_labor
   WHERE
      freigabe IS NOT NULL AND
      inaktiv IS NULL
   ORDER BY bez
";

//, 'preselect' => 'vorlage_therapie_id'
$querys['query_vorlage_therapie'] = "
   SELECT
      vorlage_therapie_id,
      bez
   FROM vorlage_therapie
   WHERE
      freigabe IS NOT NULL AND
      inaktiv IS NULL
";

//, 'preselect' => 'vorlage_dokument_id'
$querys['query_vorlage_dokument_kpr'] = "
   SELECT
      vorlage_dokument_id,
      bez
   FROM vorlage_dokument
   WHERE
      freigabe IS NOT NULL AND
      typ = 'kpr' AND
      inaktiv IS NULL
   ORDER BY
      bez ASC
";

//, 'preselect' => 'vorlage_dokument_id'
$querys['query_vorlage_dokument_br'] = "
   SELECT
      vorlage_dokument_id,
      bez
   FROM vorlage_dokument
   WHERE
      typ = 'br' AND
      freigabe IS NOT NULL AND
      inaktiv IS NULL
";

$querys['query_studie'] = "
   SELECT
      studie.studie_id,
      vorlage.bez
   FROM studie studie
      LEFT JOIN vorlage_studie vorlage    ON studie.vorlage_studie_id = vorlage.vorlage_studie_id
   WHERE
      studie.patient_id = '$patient_id' AND studie.erkrankung_id = '$erkrankung_id' AND studie.studie_id IS NOT NULL
";

$querys['query_eingriff'] = "
   SELECT
      e.eingriff_id,
      DATE_FORMAT(e.datum, '%d.%m.%Y'),
      l.bez,
      IF(e.art_diagnostik IS NOT NULL, 'lbl-eingriff-art_diagnostik', NULL),
      IF(e.art_sonstige IS NOT NULL, e.art_sonstige, NULL),
      IF(e.art_sonstige IS NOT NULL, 'lbl-eingriff-art_sonstige', NULL),
      IF(e.art_primaertumor IS NOT NULL, 'lbl-eingriff-art_primaertumor', NULL),
      IF(e.art_lk IS NOT NULL, 'lbl-eingriff-art_lk', NULL),
      IF(e.art_metastasen IS NOT NULL, 'lbl-eingriff-art_metastasen', NULL),
      IF(e.art_rezidiv IS NOT NULL, 'lbl-eingriff-art_rezidiv', NULL),
      IF(e.art_nachresektion IS NOT NULL, 'lbl-eingriff-art_nachresektion', NULL),
      IF(e.art_revision IS NOT NULL, 'lbl-eingriff-art_revision', NULL),
      IF(e.art_rekonstruktion IS NOT NULL, 'lbl-eingriff-art_rekonstruktion', NULL)
   FROM eingriff e
      LEFT JOIN l_basic l ON (e.diagnose_seite IS NOT NULL OR e.diagnose_seite != '-') AND l.klasse = 'seite' AND l.code = e.diagnose_seite
   WHERE
      e.patient_id = '{$patient_id}' AND e.erkrankung_id = '{$erkrankung_id}'
   ORDER BY
      e.datum DESC, e.diagnose_seite
";

$querys['query_therapieplan'] = "
   SELECT
      therapieplan_id,
      DATE_FORMAT(datum, '%d.%m.%Y') as bez
   FROM therapieplan
   WHERE
      erkrankung_id = '$erkrankung_id'
";


$querys['query_untersuchung'] = "
   SELECT
      u.untersuchung_id,
      DATE_FORMAT(u.datum, '%d.%m.%Y'),
      l.bez,
      CONCAT_WS(' - ', u.art, u.art_text)
   FROM untersuchung u
      LEFT JOIN l_basic l ON (u.art_seite IS NOT NULL OR u.art_seite != '-') AND l.klasse = 'seite' AND l.code = u.art_seite
   WHERE
      u.erkrankung_id = '{$erkrankung_id}' AND u.patient_id = '{$patient_id}'
   ORDER BY
      u.datum DESC, u.art
";


$querys['query_konferenz'] = "
   SELECT
      DISTINCT k.konferenz_id,
      CONCAT_WS(' ',
         bez,
         CONCAT_WS('',
            '(',
            DATE_FORMAT(k.datum, '%d.%m.%Y'),
            ')'
         )
      )
   FROM konferenz k
   WHERE
      k.final IS NULL
   ORDER BY
      k.bez ASC, k.datum DESC
";

$querys['query_moderator'] = "
      SELECT
      recht.user_id,
      CONCAT_WS(', ', user.nachname, user.vorname, org.name)
   FROM recht recht
      LEFT JOIN org org ON org.org_id = recht.org_id
      LEFT JOIN user user  ON user.user_id = recht.user_id
   WHERE
      rolle = 'moderator'
";

$querys['query_aufenthalt'] = "
   SELECT
      a.aufenthalt_id,
      CONCAT_WS(', ',
         IF(LENGTH(
             CONCAT_WS(' - ',
                 IF(a.aufnahmedatum IS NOT NULL, CONCAT_WS(' ' , 'Aufn.:', DATE_FORMAT(a.aufnahmedatum, '%d.%m.%Y')), NULL),
                 IF(a.entlassungsdatum IS NOT NULL, CONCAT_WS(' ' , 'Entl.:', DATE_FORMAT(a.entlassungsdatum, '%d.%m.%Y')), NULL)
             )),
             CONCAT_WS(' - ',
                 IF(a.aufnahmedatum IS NOT NULL, CONCAT_WS(' ' , 'Aufn.:', DATE_FORMAT(a.aufnahmedatum, '%d.%m.%Y')), NULL),
                 IF(a.entlassungsdatum IS NOT NULL, CONCAT_WS(' ' , 'Entl.:', DATE_FORMAT(a.entlassungsdatum, '%d.%m.%Y')), NULL)
             ),
             NULL
         ),
         a.aufnahmenr
      )
   FROM aufenthalt a
       LEFT JOIN qs_18_1_b qs18b ON qs18b.aufenthalt_id = a.aufenthalt_id
";

$querys['query_konferenztitel'] = "
    SELECT bez FROM vorlage_konferenztitel
";

$querys['query_fallkennzeichen'] = "SELECT code, bez FROM vorlage_fallkennzeichen ORDER BY pos";

?>
