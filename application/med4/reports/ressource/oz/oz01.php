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

   $relevantErk          = $this->_filterDisease(true);

   $having               = "
        CASE erkrankung
            WHEN 'b' THEN (diagnose LIKE 'C50%' OR diagnose IN ('D05.1','D05.7','D05.9')) AND
                           ((RIGHT(morphologie,1) IN ('2', '3') AND morphologie != '8520/2') OR anlass LIKE 'r%')
            WHEN 'p' THEN diagnose = 'C61'
            ELSE 1
        END
   ";

   $relevantSelectWhere = "ts.erkrankung_id = t.erkrankung_id AND ts.diagnose_seite IN ('B', t.diagnose_seite) AND ts.anlass = t.anlass";

   $rezidivOneStepCheck = $this->_rezidivOneStepCheck();

   $relevantSelects = array(
      "(
         SELECT
            IF(MAX(ts.nur_zweitmeinung) IS NOT NULL OR MAX(ts.nur_diagnosesicherung) IS NOT NULL OR MAX(ts.kein_fall) IS NOT NULL, 1, NULL)
         FROM tumorstatus ts
         WHERE
            {$relevantSelectWhere}
      ) AS 'nicht_zaehlen'
      ",
      "(SELECT IF(
         ts.diagnose_c19_zuordnung IS NULL AND
         ts.diagnose = 'C19',
         'C20',
         ts.diagnose_c19_zuordnung
      )                             FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.diagnose IS NOT NULL  ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS diagnose_c19_zuordnung",
      "(SELECT ts.n                 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND LOCATE('pN', ts.n) != 0 AND LOCATE('(sn)', ts.n) != 0       ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS ppn",
      "(SELECT ts.m                 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.m IS NOT NULL               ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS m",
      "(SELECT ts.g                 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.g IS NOT NULL               ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS g",
      "(SELECT ts.diagnose_seite    FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.diagnose IS NOT NULL        ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS diagnose_seite",
      "(SELECT ts.resektionsrand    FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.resektionsrand IS NOT NULL  ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS resektionsrand",
      "(SELECT ts.lk_entf           FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.lk_entf IS NOT NULL         ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS lk_entf",
      "(SELECT ts.lk_bef            FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.lk_bef IS NOT NULL          ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS lk_bef",
      "(SELECT ts.r_lokal           FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.r_lokal IS NOT NULL         ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS r_lokal",
      "(SELECT ts.datum_sicherung   FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.sicherungsgrad = 'end'      ORDER BY ts.datum_sicherung DESC LIMIT 1) AS end_datum",

      "(SELECT ts.datum_sicherung   FROM tumorstatus ts WHERE {$rezidivOneStepCheck} AND ts.diagnose_seite IN ('B', t.diagnose_seite) AND ts.rezidiv_lokal IS NOT NULL       ORDER BY ts.datum_sicherung ASC LIMIT 1) AS rezidiv_lokal_datum",
      "(SELECT ts.datum_sicherung   FROM tumorstatus ts WHERE {$rezidivOneStepCheck} AND ts.diagnose_seite IN ('B', t.diagnose_seite) AND ts.rezidiv_lk IS NOT NULL          ORDER BY ts.datum_sicherung ASC LIMIT 1) AS rezidiv_lk_datum",
      "(SELECT ts.datum_sicherung   FROM tumorstatus ts WHERE {$rezidivOneStepCheck} AND ts.diagnose_seite IN ('B', t.diagnose_seite) AND ts.rezidiv_metastasen IS NOT NULL  ORDER BY ts.datum_sicherung ASC LIMIT 1) AS rezidiv_metastasen_datum",
   );

   $preQuery = $this->_getPreQuery($having, array_merge($relevantSelects,$additionalTsSelects));

   $query = "
      SELECT
         {$additionalFields}
         sit.nachname                                                        AS 'nachname',
         sit.vorname                                                         AS 'vorname',
         sit.geburtsdatum                                                    AS 'geburtsdatum',
         sit.patient_nr                                                      AS 'patient_nr',
         sit.geschlecht                                                      AS 'geschlecht',
         sit.diagnosetyp                                                     AS 'erkrankung',
         sit.diagnose                                                        AS 'diagnose',
         {$this->_getAnlassCases()}                                          AS 'anlasslbl',
         {$this->_getPrimaerfallCases()}                                     AS 'primaerfall',
         {$this->_buildBezugsdatumCases()}                                   AS 'bezugsdatum',
         IF(sit.diagnose_seite != '-', sit.diagnose_seite, '')               AS 'diagnose_seite',

         IF(
             COUNT(DISTINCT IF(
                 LEFT(th_sys.vorlage_therapie_art,1) = 'c',
                 th_sys.therapie_systemisch_id,
                 NULL
             )) > 0,
             1,
             NULL
         )                                                                       AS 'durchg_chemo',

         IF(
            COUNT(DISTINCT IF(
               th_str.endstatus != 'abbr' OR th_str.endstatus IS NULL,
               th_str.strahlentherapie_id,
               NULL
            )) > 0,
            1,
            NULL
         )                                                                       AS 'durchg_strahlen',

         IF(COUNT(
            DISTINCT IF(
               th_sys.vorlage_therapie_art IN ('c', 'cst', 'ci') AND
               th_sys.intention IN ('kurna', 'palna'),
               th_sys.therapie_systemisch_id,
               NULL
            )
         ), 1, NULL)                                                             AS 'neoadj_chemotherapie',


         sit.morphologie                                                         AS 'icd03',

         MAX(IF(1 IN (op.art_primaertumor), op.datum, NULL))                     AS 'primaerop',

         MIN(IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = '1', s.form_date, NULL))      AS 'datum_primaer_op_rezidiv_op',

         IF(COUNT(tp.therapieplan_id) > 0, 1, null)                              AS 'therapieplan',

         IF(
             COUNT(DISTINCT IF(
                 th_str.vorlage_therapie_art = 'cst',
                 th_str.strahlentherapie_id,
                 NULL
             )),
             1,
             NULL
         )                                                                       AS 'durchg_radio',

         MAX(IF(op.art_primaertumor = 1, op.schnellschnitt, NULL))               AS 'intraop',

         MAX(IF(op.art_primaertumor = 1, op.schnellschnitt_dauer, NULL))         AS 'resektion_ergebnisdauer',

         MAX(IF(
            op.art_primaertumor = 1,
            (SELECT CONCAT_WS(', ', u.nachname, u.vorname) FROM user u WHERE u.user_id = op.operateur1_id LIMIT 1),
            NULL
         ))                                                                                     AS 'operateur_1',

         MAX(IF(
            op.art_primaertumor = 1,
            (SELECT CONCAT_WS(', ', u.nachname, u.vorname) FROM user u WHERE u.user_id = op.operateur2_id LIMIT 1),
            NULL
         ))                                                                                     AS 'operateur_2',

         GROUP_CONCAT(
            DISTINCT (
               IF(
                  s.form = 'eingriff' AND LEFT(s.report_param, 1) = 1 AND
                  SUBSTRING(s.report_param, 3, 1) IN ('B', sit.diagnose_seite) AND
                  (SELECT art_primaertumor FROM eingriff WHERE eingriff_id = s.form_id) = 1,
                  SUBSTRING(s.report_param, 5),
                  NULL
               )
            )
            SEPARATOR ' '
         )                                                                 AS 'primaer_ops',

         COUNT(
            IF(
               s.form = 'eingriff' AND
               LOCATE('5-', SUBSTRING(s.report_param, 5)) != 0 AND
               SUBSTRING(s.report_param, 3, 1) IN ('B', sit.diagnose_seite),
               1, null
            )
         )                                                                 AS 'anz_ops',

         sit.pt                                                            AS 'pt',
         sit.pn                                                            AS 'pn',
         sit.ppn                                                           AS 'ppn',
         sit.m                                                             AS 'm',
         sit.g                                                             AS 'g',
         sit.r                                                             AS 'r',
         sit.r_lokal                                                       AS 'r_lokal',
         sit.resektionsrand                                                AS 'sicherabstand',
         sit.lk_bef                                                        AS 'lk_bef',
         sit.lk_entf                                                       AS 'lk_entf',

         IF(
            COUNT(
               DISTINCT IF(
                  k.revisionsoperation = '1',
                  k.komplikation_id,
                  NULL
               )
            ) > 0
            OR
            COUNT(
               DISTINCT IF(
                  op.art_revision IS NOT NULL,
                  op.eingriff_id,
                  NULL
               )
            ),
            1,
            IF(
                COUNT(DISTINCT k.komplikation_id) > 0 AND MAX(k.revisionsoperation) = 0,
                0,
                NULL
            )
         )                                                                 AS 'revisionsop',

         GROUP_CONCAT(DISTINCT k.komplikation SEPARATOR '|')               AS 'komplikation',

         IF(
            COUNT(
               DISTINCT IF(
                  tp.grundlage = 'tk' AND
                  tp.zeitpunkt = 'prae',
                  tp.therapieplan_id,
                  NULL
               )
            ) OR
            COUNT(
               DISTINCT IF(
                  s.form = 'konferenz_patient' AND
                  LEFT(s.report_param, 4) = 'prae' AND
                  SUBSTRING(s.report_param, 6) != '' AND
                  SUBSTRING(s.report_param, 6) BETWEEN sit.start_date AND sit.end_date,
                  s.form_id,
                  NULL
               )
            ),
            1, NULL
         )                                                                                         AS 'praeop_tumorkonf',

         IF(
            COUNT(
               DISTINCT IF(
                  tp.grundlage = 'tk' AND
                  tp.zeitpunkt = 'post',
                  tp.therapieplan_id,
                  NULL
               )
            ) OR
            COUNT(
               DISTINCT IF(
                  s.form = 'konferenz_patient' AND
                  LEFT(s.report_param, 4) = 'post' AND
                  SUBSTRING(s.report_param, 6) != '' AND
                  SUBSTRING(s.report_param, 6) BETWEEN sit.start_date AND sit.end_date,
                  s.form_id,
                  NULL
               )
            ),
            1, NULL
         )                                                                                         AS 'postop_tumorkonf',

         GROUP_CONCAT(nw_str_th.bez SEPARATOR ', ')                                                AS 'nebenwirkung_radio',

         GROUP_CONCAT(
            IF(
               nw.therapie_systemisch_id IS NOT NULL AND (SELECT 1 FROM therapie_systemisch WHERE therapie_systemisch_id = nw.therapie_systemisch_id AND vorlage_therapie_art = 'cst') = 1,
               (SELECT bez FROM l_nci WHERE code = nw.nci_code),
               NULL
            )
            SEPARATOR ', '
         )                                                                                         AS 'nebenwirkung_chemo_radio',

           GROUP_CONCAT(DISTINCT
               IF(s.form = 'studie',
                   CONCAT_WS('|', s.form_id, DATE_FORMAT(s.report_param, '%d.%m.%Y')),
                   NULL
               )
               SEPARATOR ', '
            )                                                                  AS 'datum_studie',

            COUNT(DISTINCT IF(s.form = 'studie', s.form_id, NULL))            AS 'count_studie',

         MAX(DISTINCT b.psychoonkologie)                                                           AS 'psychoonk',

         MAX(DISTINCT b.sozialdienst)                                                              AS 'sozialdienst',

         IF(COUNT(IF(s.form = 'fragebogen' AND s.report_param IN ({$this->_getPreSelect('zufrFragebogen')}), s.form_id, NULL)),1, NULL) AS 'befragungsbogen',

         IF(
            COUNT(
               DISTINCT IF(
                  auf.aufnahmedatum IS NOT NULL AND auf.entlassungsdatum IS NOT NULL AND
                  sit.end_datum BETWEEN auf.aufnahmedatum AND auf.entlassungsdatum,
                  auf.aufenthalt_id,
                  NULL
               )
            ) > 0,
            1,
            NULL
         )                                                                                         AS 'stationaer',

         MAX(n.datum)                                                                              AS 'nachsorge',

         IF(sit.rezidiv_lokal_datum IS NOT NULL AND sit.rezidiv_lk_datum IS NOT NULL,
            IF(
               sit.rezidiv_lokal_datum < sit.rezidiv_lk_datum,
               sit.rezidiv_lokal_datum,
               sit.rezidiv_lk_datum
            ),
            IFNULL(sit.rezidiv_lokal_datum, sit.rezidiv_lk_datum)
         )                                                                                         AS 'lokalrezidiv',

         sit.rezidiv_metastasen_datum                                                              AS 'fernmetast',

         IF(
            MAX(x.abschluss_grund) IS NOT NULL AND MAX(x.abschluss_grund) = 'lost',
            1,
            IF(
               MAX(x.abschluss_grund) IS NOT NULL AND MAX(x.abschluss_grund) != 'lost',
               0,
               NULL
            )
        )                                                                                          AS 'losttofu',

         MAX(x.todesdatum)                                                                         AS 'todesdatum',

         IF(
            MAX(x.tod_tumorassoziation) IS NOT NULL AND MAX(x.tod_tumorassoziation) IN ('tott', 'totn'),
            1,
            IF(
               MAX(x.tod_tumorassoziation) IS NOT NULL AND MAX(x.tod_tumorassoziation) NOT IN ('tott', 'totn'),
               0,
               NULL
            )
        )                                                                                           AS 'tod_tumorbedingt',

         sit.diagnosetyp,
         sit.anlass,
         sit.start_date,
         sit.end_date,
         sit.erkrankung_id,
         sit.patient_id,
         sit.diagnose_c19_zuordnung                                                                AS 'zugeordnet_zu',
         IF(
             sit.diagnosetyp = 'prostata' AND sit.anlass = 'p' AND (
             COUNT(DISTINCT IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = 1, s.form_id, NULL)) = 0 AND
             COUNT(DISTINCT th_sys.therapie_systemisch_id) = 0 AND
             COUNT(DISTINCT th_str.strahlentherapie_id) = 0 AND
             COUNT(DISTINCT IF('1' IN (tp.watchful_waiting, tp.active_surveillance), tp.therapieplan_id, NULL)) = 0
         ), 1, 0)                                                              AS 'prostata_nz'
      FROM (
         SELECT
            sit.*,
            {$this->_buildDiagCase()}
         FROM ($preQuery) sit
      ) sit
         {$this->_innerStatus()}

         LEFT JOIN histologie h                                   ON s.form = 'histologie' AND h.histologie_id  = s.form_id AND
                                                                     h.diagnose_seite IN ('B', sit.diagnose_seite)
         LEFT JOIN zytologie z                                    ON s.form = 'zytologie' AND z.zytologie_id  = s.form_id
         LEFT JOIN therapie_systemisch th_sys                     ON s.form = 'therapie_systemisch' AND th_sys.therapie_systemisch_id = s.form_id
         LEFT JOIN strahlentherapie th_str                        ON s.form = 'strahlentherapie' AND th_str.strahlentherapie_id = s.form_id
         LEFT JOIN sonstige_therapie th_son                       ON s.form = 'sonstige_therapie' AND th_son.sonstige_therapie_id = s.form_id

         LEFT JOIN eingriff op                                    ON s.form = 'eingriff' AND op.eingriff_id = s.form_id AND
                                                                     op.diagnose_seite IN ('B', sit.diagnose_seite)

         LEFT JOIN therapieplan tp                                ON s.form = 'therapieplan' AND tp.therapieplan_id = s.form_id

         {$this->_statusJoin('komplikation k')}

         LEFT JOIN beratung b                                     ON s.form = 'beratung' AND b.beratung_id = s.form_id

         LEFT JOIN nachsorge n                                    ON s.form = 'nachsorge' AND LOCATE(CONCAT_WS('','-',sit.erkrankung_id,'-'), s.report_param) > 0 AND
                                                                     n.nachsorge_id = s.form_id
         LEFT JOIN nebenwirkung nw                                ON s.form = 'nebenwirkung' AND nw.nebenwirkung_id = s.form_id
            LEFT JOIN l_nci nw_str_th                             ON nw.strahlentherapie_id IS NOT NULL AND nw_str_th.code = nw.nci_code

         LEFT JOIN aufenthalt auf                                 ON s.form = 'aufenthalt' AND auf.aufenthalt_id = s.form_id

         LEFT JOIN abschluss x                                    ON s.form = 'abschluss' AND x.abschluss_id = s.form_id

         {$additionalJoins}
      WHERE
         {$this->_getNcState()} AND
         sit.diagnosetyp IS NOT NULL
      GROUP BY
         sit.patient_id,
         sit.erkrankung_id,
         sit.anlass,
         sit.diagnose_seite
      HAVING
         {$this->_buildHaving()}
         {$this->_getNcState('oz01pz')}
         {$additionalCondition}
      ORDER BY
         nachname, vorname, erkrankung, bezugsdatum
   ";

   $data = sql_query_array($this->_db, $query);
   $haeute = array();

   $lKomplikation = getLookup($this->_db, 'komplikation');

   foreach ($data as $i => &$record) {
       $diagnosetyp      = $record['diagnosetyp'];

       $data[$i]['datum_studie'] = $this->_removeIdentifier($record['datum_studie']);

       //Komplikation
       $komplikationen = strlen($record['komplikation']) > 0 ? explode('|', $record['komplikation']) : null;

       if ($komplikationen !== null) {
           $tmp = array();

           foreach($komplikationen as $komplikation) {
               if (isset($lKomplikation[$komplikation]) === true) {
                   $tmp[] = $lKomplikation[$komplikation];
               }
           }

           asort($tmp);

           $record['komplikation'] = implode(',', $tmp);
       }

       //Haut doppelzählung
       if ($diagnosetyp === 'haut') {
            $haeute[$record['patient_id']][] = array('bzg' => $record['bezugsdatum'], 'key' => $i);
       }

       if ($diagnosetyp == 'darm') {
          $erkrankungId     = $record['erkrankung_id'];
          $patientId        = $record['patient_id'];
          $minDate          = $record['start_date'];
          $maxDate          = $record['end_date'];
          $primaerfall      = $record['primaerfall'];

          // check
          if ($primaerfall == 1 && $record['neoadj_chemotherapie'] == 1 && strlen($record['datum_primaer_op_rezidiv_op']) == 0) {
             $data[$i]['primaerfall'] = $primaerfall = 3;
          }

            //Zweiterkrankung
          if ($primaerfall == 1) {
               $diagnoseZuordnung = $record['zugeordnet_zu'];
               $diagnose          = $record['diagnose'];
               $bezugsdatum       = $record['bezugsdatum'];
               $zuordnung         = strlen($diagnoseZuordnung) ? $diagnoseZuordnung : substr($diagnose, 0, 3);

               $sit = "
                SELECT
                   e.patient_id,
                   e.erkrankung_id,
                   t.anlass,

                    (SELECT
                      IF(MAX(ts.nur_zweitmeinung) IS NOT NULL OR MAX(ts.nur_diagnosesicherung) IS NOT NULL OR MAX(ts.kein_fall) IS NOT NULL, 1, NULL)
                   FROM tumorstatus ts
                   WHERE
                      {$relevantSelectWhere}
                   ) AS 'nicht_zaehlen',

                   IF(x.first_date=MIN(IF(t2.anlass = t.anlass, t2.datum_sicherung, null)), '0000-00-00', MIN(t.datum_sicherung))                                       AS 'start_date',
                   DATE_SUB(IFNULL(MIN(IF(t2.anlass != t.anlass AND t2.datum_sicherung > t.datum_sicherung, t2.datum_sicherung,null) ), '9999-12-31'), INTERVAL 1 DAY)  AS 'end_date',

                   (SELECT ts.diagnose FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass = t.anlass AND ts.diagnose IS NOT NULL ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1)
                   AS diagnose,

                   (SELECT IF(ts.diagnose_c19_zuordnung IS NULL AND ts.diagnose = 'C19', 'C20', ts.diagnose_c19_zuordnung) FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass = t.anlass AND ts.diagnose IS NOT NULL ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1)
                   AS diagnose_c19_zuordnung

                FROM erkrankung e
                   INNER JOIN tumorstatus t    ON t.erkrankung_id = e.erkrankung_id
                   LEFT JOIN tumorstatus t2    ON t2.erkrankung_id = e.erkrankung_id

                  INNER JOIN (
                      SELECT
                          erkrankung_id,
                          MIN(datum_sicherung) AS first_date
                      FROM
                          tumorstatus
                      WHERE patient_id = '{$patientId}' AND erkrankung_id != '{$erkrankungId}'
                      GROUP BY
                        erkrankung_id
                  ) x                          ON x.erkrankung_id = e.erkrankung_id

                WHERE
                   e.patient_id      = '{$patientId}' AND
                   e.erkrankung      = 'd' AND
                   e.erkrankung_id   != '{$erkrankungId}' AND
                   t.anlass          = 'p'
                GROUP BY
                   e.patient_id,
                   e.erkrankung_id,
                   t.anlass
                ORDER BY NULL
             ";


               $query = "
                  SELECT

                  IFNULL(MIN(primaerop.datum), MIN(h.datum))    AS 'bezugsdatum',
                  sit.diagnose                                  AS 'diagnose',
                  sit.diagnose_c19_zuordnung                    AS 'zugeordnet_zu',

                  IF(COUNT(
                     DISTINCT IF(
                        th_sys.vorlage_therapie_art IN ('c', 'cst', 'ci') AND
                        th_sys.intention IN ('kurna', 'palna'),
                        th_sys.therapie_systemisch_id,
                        NULL
                     )
                  ), 1, NULL)                                 AS 'neoadj_chemotherapie',

                  MIN(primaerop.datum)                        AS 'datum_primaer_op_rezidiv_op'

                  FROM ($sit) sit
                     LEFT JOIN eingriff primaerop                  ON primaerop.erkrankung_id = sit.erkrankung_id AND
                                                                      primaerop.datum BETWEEN sit.start_date AND sit.end_date AND
                                                                      '1' IN (primaerop.art_primaertumor, primaerop.art_rezidiv)

                     LEFT JOIN histologie h                        ON h.erkrankung_id = sit.erkrankung_id AND
                                                                      h.datum BETWEEN sit.start_date AND sit.end_date

                     LEFT JOIN therapie_systemisch th_sys          ON th_sys.erkrankung_id = sit.erkrankung_id AND
                                                                      th_sys.beginn BETWEEN sit.start_date AND sit.end_date
                  WHERE
                     {$this->_getNcState()}
                  GROUP BY
                     sit.patient_id,
                     sit.erkrankung_id,
                     sit.anlass
               ";


               if (strlen($bezugsdatum) == 0) {
                   $record['primaerfall'] = 0;
               } else {
                   $moeglZweiterkrankungen = sql_query_array($this->_db, $query);

                   if (count($moeglZweiterkrankungen) > 0) {
                       foreach ($moeglZweiterkrankungen as $erk) {
                          //continue if current checked dataset is no primary case due chemo and op check
                          if ($erk['neoadj_chemotherapie'] == 1 && strlen($erk['datum_primaer_op_rezidiv_op']) == 0) {
                             continue;
                          }

                          $secondDiagnoseZuordnung = $erk['zugeordnet_zu'];
                          $secondDiagnose          = $erk['diagnose'];
                          $secondBezugsdatum       = $erk['bezugsdatum'];
                          $secondZuordnung         = strlen($secondDiagnoseZuordnung) ? $secondDiagnoseZuordnung : substr($secondDiagnose, 0, 3);

                          if (strlen($secondBezugsdatum) > 0 && strlen($secondZuordnung) > 0) {

                              //Wenn gleiche Erkrankung und durchlaufener Anlass hat kleineres Bezugsdatum
                              if ($secondZuordnung == $zuordnung && $bezugsdatum > $secondBezugsdatum) {
                                  $record['primaerfall'] = 2;
                              }
                          }
                       }
                   }
               }
            }
        } //Darm ende

    }

    //only one haut-primary-case per patient per year -.-
    $data = $this->_unsetPrimaryHauete($data, $haeute);

?>