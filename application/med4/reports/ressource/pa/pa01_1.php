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

   $relevantSelectWhere = "ts.erkrankung_id = t.erkrankung_id AND ts.anlass = t.anlass";

   $stageCalc = stageCalc::create($this->_db, $this->_params['sub']);

   $relevantSelects = array(
      $stageCalc->select('c')  . "AS 'uicc_prae'",
      $stageCalc->select()     . "AS 'uicc'",
      "(
         SELECT
            IF(MAX(ts.nur_zweitmeinung) IS NOT NULL OR MAX(ts.nur_diagnosesicherung) IS NOT NULL OR MAX(ts.kein_fall) IS NOT NULL, 1, NULL)
         FROM tumorstatus ts
         WHERE
            {$relevantSelectWhere}
      ) AS 'nicht_zaehlen'
      ",
      "(SELECT ts.t        FROM tumorstatus ts WHERE {$relevantSelectWhere} AND LEFT(ts.t, 1) = 'c'         ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS ct",
      "(SELECT ts.lk_entf  FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.lk_entf IS NOT NULL      ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS lk_entf",
      "(SELECT ts.lk_bef   FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.lk_bef IS NOT NULL       ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS lk_bef",
      "(SELECT ts.l        FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.l IS NOT NULL            ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS l",
      "(SELECT ts.m        FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.m IS NOT NULL            ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS m",
      "(SELECT ts.g        FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.g IS NOT NULL            ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS g",
      "(SELECT ts.v        FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.v IS NOT NULL            ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS v",
      "(SELECT ts.ppn      FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.ppn IS NOT NULL          ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS ppn",
      "(SELECT ts.r_lokal  FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.r_lokal IS NOT NULL      ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS r_lokal",
    );

   $preQuery = $this->_getPreQuery('diagnose LIKE "C25%"', array_merge($relevantSelects,$additionalTsSelects));

   $primOpDate = "(
      SELECT
         st.form_date
      FROM status st
      WHERE
         st.erkrankung_id = sit.erkrankung_id AND
         st.form = 'eingriff' AND
         st.form_date BETWEEN sit.start_date AND sit.end_date AND
         LEFT(st.report_param, 1) = 1
   )";

   $resektionsflaeche = getLookup($this->_db, 'status_resektionsrand');

   //Codes für ERCP Untersuchungen
   $ercp = "LOCATE('3-e05.y', u.art) != 0 OR
            LOCATE('1-642', u.art) != 0 OR
            LOCATE('5-513', u.art) != 0
   ";

   //Codes für EUS Untersuchungen
   $eus = "LOCATE('3-05', u.art) != 0 OR
           LOCATE('1-445', u.art) != 0 OR
           LOCATE('1-446', u.art) != 0 OR
           LOCATE('1-447', u.art) != 0 OR
           LOCATE('3-e17.y', u.art) != 0
   ";


    $vct = sql_query_array($this->_db, "
        SELECT
            vt.vorlage_therapie_id,
            GROUP_CONCAT(DISTINCT vtw.wirkstoff) as wirkstoff
        FROM vorlage_therapie vt
            LEFT JOIN vorlage_therapie_wirkstoff vtw ON vt.vorlage_therapie_id = vtw.vorlage_therapie_id
        GROUP BY
            vt.vorlage_therapie_id
        HAVING wirkstoff LIKE '%gemcitabin%' OR (wirkstoff LIKE '%folinsaeure%' AND wirkstoff LIKE '%fluorouracil%')
    ");

    $vctIds = array(0);

    foreach ($vct as $therapy) {
        $vctIds[] = $therapy['vorlage_therapie_id'];
    }

   $query = "
      SELECT
         {$additionalFields}
         sit.nachname                                                        AS 'nachname',
         sit.vorname                                                         AS 'vorname',
         sit.geburtsdatum                                                    AS 'geburtsdatum',
         sit.patient_nr                                                      AS 'patient_nr',
         IF(sit.anlass = 'p',
            IF(LEFT(sit.morphologie, 4) != '8453', 1, 0),
            0
         )                                                                   AS 'primaerfall',
         {$this->_getAnlassCases()}                                          AS 'anlass_case',
         IF(
            sit.anlass LIKE 'r%' AND MIN(h.datum) IS NULL,
            IF(sit.start_date = '0000-00-00', sit.start_date_rezidiv, sit.start_date),
            MIN(h.datum)
         )                                                                   AS 'bezugsdatum',

         sit.diagnose                                                        AS 'diagnose',

         sit.morphologie                                                     AS 'morphologie',

         IF(
             COUNT(DISTINCT IF( sit.anlass = 'p' AND
                 s.form = 'eingriff' AND (
                 LOCATE('5-524', SUBSTRING(s.report_param, 5)) != 0 OR
                 LOCATE('5-525', SUBSTRING(s.report_param, 5)) != 0),
                 s.form_id,
                 NULL
             )),
             IF(LEFT(sit.morphologie, 4) != '8453', 1, 0),
             NULL
         )                                                                    AS 'op_primaerfall',

         IF(
            COUNT(DISTINCT IF(
               s.form = 'eingriff' AND
               (LOCATE('5-524', SUBSTRING(s.report_param, 5)) != 0 OR LOCATE('5-525', SUBSTRING(s.report_param, 5)) != 0)
               AND (SELECT e.notfall FROM eingriff e WHERE e.eingriff_id = s.form_id) IS NOT NULL,
               s.form_id,
               NULL
            )), 1, NULL
         )                                                                    AS 'notfall',

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
         )                                                                AS 'tumorkonf_praeop',

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
         )                                                                 AS 'tumorkonf_postop',

         CASE (SELECT
            intention
          FROM therapieplan
          WHERE
            FIND_IN_SET(therapieplan_id, GROUP_CONCAT(tp.therapieplan_id SEPARATOR ',')) > 0
          ORDER BY datum DESC
          LIMIT 1)
            WHEN 'kur' THEN 'kurativ'
            WHEN 'pal' THEN 'palliativ'
         END                                                               AS 'gesamttherapie_intention',

         MAX(
            DISTINCT IF(
               s.form = 'eingriff' AND (
               LOCATE('5-524', SUBSTRING(s.report_param, 5)) != 0 OR
               LOCATE('5-525', SUBSTRING(s.report_param, 5)) != 0),
               s.form_date,
               NULL
            )
         )                                                                 AS 'primaeroperation',

         IF(
             COUNT(DISTINCT IF(
                 s.form = 'eingriff' AND (
                 LOCATE('5-e22.y', SUBSTRING(s.report_param, 5)) != 0 OR
                 LOCATE('5-402', SUBSTRING(s.report_param, 5)) != 0 OR
                 LOCATE('5-403', SUBSTRING(s.report_param, 5)) != 0 OR
                 LOCATE('5-404', SUBSTRING(s.report_param, 5)) != 0 OR
                 LOCATE('5-407', SUBSTRING(s.report_param, 5)) != 0 OR
                 LOCATE('5-406', SUBSTRING(s.report_param, 5)) != 0),
                 s.form_id,
                 NULL
             )), 1, NULL
         )                                                                    AS 'lymphadenektomie',

         COUNT(DISTINCT IF(
               ({$ercp}),
               u.untersuchung_id,
               NULL
            )
         )                                                                    AS 'ercp',

         COUNT(DISTINCT IF(
               ({$eus}),
               u.untersuchung_id,
               NULL
            )
         )                                                                    AS 'eus',

         GROUP_CONCAT(DISTINCT IF(
             ({$ercp}),
             u.untersuchung_id,
             NULL
         ))                                                                    AS 'ercp_untersuchungen',

         GROUP_CONCAT(DISTINCT IF(
               ({$eus}),
               u.untersuchung_id,
               NULL
            )
         )                                                                    AS 'eus_untersuchungen',

         GROUP_CONCAT(DISTINCT
            IF(
               k.komplikation = 'pank' AND k.untersuchung_id IS NOT NULL,
               k.untersuchung_id,
               NULL
            )
         )                                                                    AS 'pankreatitis',

         GROUP_CONCAT(DISTINCT
            IF(
               k.komplikation IN ('blut', 'per') AND k.untersuchung_id IS NOT NULL,
               k.untersuchung_id,
               NULL
            )
         )                                                                    AS 'blutung',

         GROUP_CONCAT(DISTINCT
            IF(
               k.untersuchung_id IS NOT NULL,
               k.untersuchung_id,
               NULL
            )
         )                                                                       AS 'eus_komplikationen',


         COUNT(DISTINCT IF(
             h.art = 'po',
             h.histologie_id,
             NULL
         ))                                                                      AS 'patho_befund',

         NULL                                                                    AS 'patho_aufarbeitung',

         ## Beachten: UICC wird am Ende nochmals nachverarbeitet! ##

         COUNT(DISTINCT IF(
            h.art = 'po' AND
            h.pt LIKE 'p%' AND
            h.pn LIKE 'p%' AND
            h.g IS NOT NULL AND
            h.lk_bef IS NOT NULL AND
            h.lk_entf IS NOT NULL AND
            SUBSTRING(h.pm, 2) != 'MX',
            h.histologie_id,
            NULL
         ))                                                                      AS 'patho_befund_vollstaendig',

         sit.uicc_prae                                                           AS 'uicc_prae',
         null                                                                    AS 'uicc_nach_neoadj_th',
         sit.uicc                                                                AS 'uicc',

         sit.pt            AS 'pt',
         sit.pn            AS 'pn',
         sit.m             as 'm',
         sit.lk_entf       AS 'lk_untersucht',
         sit.lk_bef        AS 'lk_befallen',
         sit.g             AS 'g',
         sit.r             AS 'r',
         sit.r_lokal       AS 'r_lokal',

         GROUP_CONCAT(IF(h.art = 'po', h.status_resektionsrand_organ, NULL) ORDER BY h.datum DESC)    AS 'status_resektion',

         IF(
            LENGTH(
               CONCAT_WS(
                  ',',
                  GROUP_CONCAT(
                     IF(
                        h.art = 'po',
                        h.status_resektionsrand_circumferentiell,
                        NULL
                     )
                  ),
                  GROUP_CONCAT(
                     IF(
                        h.art = 'po',
                        h.resektionsrand_circumferentiell,
                        NULL
                     )
                  )
               )
            ) > 0,
            1,
            NULL
         )                                                                                      AS 'status_rand',

         sit.l                                                                                  AS 'l',
         sit.v                                                                                  AS 'v',
         sit.ppn                                                                                AS 'pn_invasion',

         IFNULL(MAX(h.lk_mikrometastasen), 0)                                                   AS 'mikrometast',

         IF(COUNT(
            DISTINCT IF(
               th_sys.vorlage_therapie_art IN ('c','ci','cst') AND
               th_sys.intention IN ('kura', 'pala'),
               th_sys.therapie_systemisch_id,
               NULL
            )
         ), 1, NULL)                                                                               AS 'adjuvante_chemo',


        IF(COUNT(
            DISTINCT IF(
               th_sys.vorlage_therapie_art IN ('c','ci','cst') AND
               th_sys.intention IN ('kura', 'pala') AND
               th_sys.vorlage_therapie_id IN (" . implode(',', $vctIds) . "),
               th_sys.therapie_systemisch_id,
               NULL
            )
         ), 1, NULL)                                                                               AS 'adjuvante_chemo_gem',

         IF(COUNT(
            DISTINCT IF(
               tp.intention = 'pal',
               tp.therapieplan_id,
               NULL
            )
         ), 1, NULL)                                                                               AS 'palli_situation',


         GROUP_CONCAT(DISTINCT IF(a.ecog IS NOT NULL, CONCAT_WS(',', a.datum, a.ecog), NULL) SEPARATOR '|') AS ecog,

         IF(COUNT(
            DISTINCT IF(
               th_sys.vorlage_therapie_art IN ('c','ci','cst') AND
               th_sys.intention IN ('pal', 'pala', 'palna'),
               th_sys.therapie_systemisch_id,
               NULL
            )
         ), 1, NULL)                                                                               AS 'palli_chemo',

         IF(COUNT(IF(s.form = 'fragebogen' AND
                     s.report_param IN ({$this->_getPreSelect('zufrFragebogen')}),
                     s.form_id,
                     NULL)
             ) > 0,
             1,
             NULL
         )                                                                                        AS 'befragunsbogen',

         GROUP_CONCAT(DISTINCT
               IF(s.form = 'studie',
                   CONCAT_WS('|', s.form_id, DATE_FORMAT(s.report_param, '%d.%m.%Y')),
                   NULL
               )
               SEPARATOR ', '
            )                                                                  AS 'datum_studie',

         COUNT(DISTINCT IF(s.form = 'studie', s.form_id, NULL))            AS 'count_studie',

         GROUP_CONCAT(DISTINCT
            CONCAT_WS(',', n.datum, n.response_klinisch)
            SEPARATOR '|'
         )                                                                                      AS response,

         MAX(DISTINCT
            IF(
               b.psychoonkologie IS NOT NULL,
               IF(
                  b.psychoonkologie = 0,
                  b.psychoonkologie,
                  IF (b.psychoonkologie_dauer >= 25, 1, 0)
                ),
               NULL
            )
         )                                                                       AS 'psychoonk',

         MAX(DISTINCT b.sozialdienst)                                            AS 'sozialdienst',

         MAX(
            IF(
               s.form = 'nachsorge' AND
               LOCATE(CONCAT_WS('','-',sit.erkrankung_id,'-'), s.report_param) > 0,
               s.form_date,
               NULL
            )
         )                                                                       AS 'letzte_nachsorge',

         IF(
            MAX(x.abschluss_grund) IS NOT NULL AND MAX(x.abschluss_grund) = 'lost',
            1,
            IF(
               MAX(x.abschluss_grund) IS NOT NULL AND MAX(x.abschluss_grund) != 'lost',
               0,
               NULL
            )
         )                                                                       AS 'losttofu',

         MAX(x.todesdatum)                                                       AS 'todesdatum',

         IF(
            MAX(x.tod_tumorassoziation) IS NOT NULL AND MAX(x.tod_tumorassoziation) IN ('tott', 'totn'),
            1,
            IF(
               MAX(x.tod_tumorassoziation) IS NOT NULL AND MAX(x.tod_tumorassoziation) NOT IN ('tott', 'totn'),
               0,
               NULL
            )
         )                                                                       AS 'tod_tumorbedingt',

         MAX(rez.datum_sicherung)                                                AS 'rezidiv_datum',

         CONCAT_WS('|',
            MIN(IF(s.form = 'sonstige_therapie', s.form_date, NULL)),
            MIN(th_sys.beginn),
            MIN(th_str.beginn),
            MIN(IF('1' IN (op.art_primaertumor, op.art_lk,op.art_metastasen,op.art_rezidiv,op.art_nachresektion,op.art_revision), op.datum, NULL)),
            '9999-12-31'
         )                                                                       AS 'max_uicc',

         sit.anlass,
         sit.start_date,
         sit.end_date,
         sit.erkrankung_id,
         sit.patient_id

      FROM ($preQuery) sit
         {$this->_innerStatus()}
         {$this->_statusJoin('anamnese a')}
         {$this->_statusJoin('histologie h')}
         {$this->_statusJoin('eingriff op')}
         {$this->_statusJoin('therapieplan tp')}
         {$this->_statusJoin('untersuchung u')}
         {$this->_statusJoin('komplikation k')}
         {$this->_statusJoin('therapie_systemisch th_sys')}
         {$this->_statusJoin('strahlentherapie th_str')}
         {$this->_statusJoin('beratung b')}
         {$this->_statusJoin('abschluss x')}

         LEFT JOIN tumorstatus rez                                ON rez.erkrankung_id = sit.erkrankung_id AND LEFT(rez.anlass, 1) = 'r'

         LEFT JOIN nachsorge n                                    ON s.form = 'nachsorge' AND LOCATE(CONCAT_WS('','-',sit.erkrankung_id,'-'), s.report_param) > 0 AND
                                                                     n.nachsorge_id = s.form_id

         {$additionalJoins}
      WHERE
         {$this->_getNcState()}
      GROUP BY
         sit.patient_id,
         sit.erkrankung_id,
         sit.anlass
      HAVING
         {$this->_buildHaving()}
         {$additionalCondition}
      ORDER BY
         nachname, vorname, bezugsdatum
   ";

   $data = sql_query_array($this->_db, $query);

    $pathoReprocessing = array(
        'pt', 'pn', 'lk_untersucht', 'lk_befallen', 'g', 'r', 'status_resektion', 'status_rand', 'l', 'v', 'pn_invasion'
    );

    $lg = getLookup($this->_db, 'g');

    $lm = array_merge(getLookup($this->_db, 'cm'), getLookup($this->_db, 'pm'));

   foreach ($data as $i => &$record) {

      $ercpUntersuchungen  = explode(',', $record['ercp_untersuchungen']);
      $eusUntersuchungen   = explode(',', $record['eus_untersuchungen']);

      $data[$i]['datum_studie'] = $this->_removeIdentifier($record['datum_studie']);

      //UICC Berechnung
      $data[$i]['uicc_prae']           = $stageCalc->calcToMaxDate($record['uicc_prae'], min(explode('|', $record['max_uicc'])));
      $data[$i]['uicc']                = $stageCalc->calc($record['uicc']);
      $data[$i]['uicc_nach_neoadj_th'] = $stageCalc->getCacheValue('tnm_praefix');

      unset($record['eus_untersuchungen']);
      unset($record['ercp_untersuchungen']);


      //Pankreatitis
      $pankreatitis = NULL;
      foreach (explode(',', $record['pankreatitis']) AS $uId) {
         if (strlen(trim($uId)) > 0) {
            if (in_array($uId, $ercpUntersuchungen) === true) {
               $pankreatitis = 1;
               break;
            }
         }
      }

      $record['pankreatitis'] = $pankreatitis;

      //Blutung
      $blutung = NULL;
      foreach (explode(',', $record['blutung']) AS $uId) {
         if (strlen(trim($uId)) > 0) {
            if (in_array($uId, $ercpUntersuchungen) === true) {
               $blutung = 1;
               break;
            }
         }
      }

      $record['blutung'] = $blutung;

      //EUS Komplikation
      $eus = NULL;
      foreach (explode(',', $record['eus_komplikationen']) AS $uId) {
         if (strlen(trim($uId)) > 0) {
            if (in_array($uId, $eusUntersuchungen) === true) {
               $eus = 1;
               break;
            }
         }
      }

      $record['eus_komplikationen'] = $eus;

      //Resektionsflaeche
      if (strlen($record['status_resektion']) > 0) {
         $rs = reset(explode(',', $record['status_resektion']));
         $record['status_resektion'] = $resektionsflaeche[$rs];
      }

       $allFilled = 1;

       foreach ($pathoReprocessing as $fieldNames) {
          if (strlen($record[$fieldNames]) == 0) {
              $allFilled = null;
              break;
          }
       }

       $record['patho_aufarbeitung'] = $allFilled;

       $record['response'] = $this->_selectMinByDate($record['response']);

       $record['m'] = array_key_exists($record['m'], $lm) ? $lm[$record['m']] : null;
       $record['g'] = array_key_exists($record['g'], $lg) ? $lg[$record['g']] : null;

       $record['ecog'] = $this->_selectMaxByDate($record['ecog']);
   }

?>
