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

    $relevantSelectOrder = "ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC";

    $relevantSelects = array(
      'e.erkrankung_relevant_haut',
      $stageCalc->select('c', 'ajcc')  . "AS 'ajcc_prae'",
      $stageCalc->select(null, 'ajcc') . "AS 'ajcc'",
      "(
         SELECT
            IF(MAX(ts.nur_zweitmeinung) IS NOT NULL OR MAX(ts.nur_diagnosesicherung) IS NOT NULL OR MAX(ts.kein_fall) IS NOT NULL, 1, NULL)
         FROM tumorstatus ts
         WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass = t.anlass
      ) AS 'nicht_zaehlen'",
      "(SELECT ts.n                 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND LEFT(ts.n, 1) = 'c' {$relevantSelectOrder} LIMIT 1) AS cn",
      "(SELECT ts.n                 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.n IS NOT NULL {$relevantSelectOrder} LIMIT 1) AS n",
      "(SELECT ts.t                 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.t IS NOT NULL {$relevantSelectOrder} LIMIT 1) AS t",

      "(SELECT IF(ts.lokalisation = 'C69.4', 1, NULL) FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.lokalisation IS NOT NULL {$relevantSelectOrder} LIMIT 1) AS uvea",
      "(SELECT IF(ts.lokalisation = 'C69.0', 1, NULL) FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.lokalisation IS NOT NULL {$relevantSelectOrder} LIMIT 1) AS konjunktiva",

      "(SELECT IF(ts.lokalisation IN ('C00.3', 'C00.4', 'C00.5', 'C03.0', 'C03.1', 'C03.9', 'C06.0', 'C30.0'),1, NULL) FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.lokalisation IS NOT NULL {$relevantSelectOrder} LIMIT 1) AS schleimhaut",
      "(SELECT ts.m                 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.m IS NOT NULL {$relevantSelectOrder} LIMIT 1) AS m",
      "(SELECT ts.datum_sicherung   FROM tumorstatus ts WHERE {$this->_rezidivOneStepCheck()} ORDER BY ts.datum_sicherung ASC LIMIT 1) AS rezidiv_datum",
      "(SELECT ts.resektionsrand    FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.resektionsrand IS NOT NULL  ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS resektionsrand",
    );

   $preQuery = $this->_getPreQuery('diagnose LIKE "C%"', array_merge($relevantSelects, $additionalTsSelects));

   $primaryCases = $this->_detectPrimaryCases($preQuery);

   //LDH bestimmung ids
   $this->_addPreSelect('ldhIds', "
        SELECT
            DISTINCT labor_id AS 'id'
        FROM labor_wert
        WHERE
           erkrankung_id IN ({$this->_filteredDiseases}) AND
           parameter = 'ldh' AND
           wert IS NOT NULL
   ");

   //Breslow <= 2
   $this->_addPreSelect('breslowkl2', "
       SELECT
           DISTINCT histologie_id AS 'id'
       FROM histologie_einzel
       WHERE
           erkrankung_id IN ({$this->_filteredDiseases}) AND
           tumordicke <= 2
   ");

   //Breslow > 2
   $this->_addPreSelect('breslowgr2', "
       SELECT
           DISTINCT histologie_id AS 'id'
       FROM histologie_einzel
       WHERE
           erkrankung_id IN ({$this->_filteredDiseases}) AND
           tumordicke > 2
   ");

   //Breslow >= 1
   $this->_addPreSelect('breslowgr1', "
       SELECT
           DISTINCT histologie_id AS 'id'
       FROM histologie_einzel
       WHERE
           erkrankung_id IN ({$this->_filteredDiseases}) AND
           tumordicke >= 1
   ");

   $query = "
      SELECT
        {$additionalFields}
        sit.nachname                                                                  AS nachname,
        sit.vorname                                                                   AS vorname,
        sit.geburtsdatum                                                              AS geburtsdatum,
        sit.patient_nr                                                                AS patient_nr,
        null                                                                          AS primaerfall,
        sit.erkrankung_relevant_haut                                                  AS relevant,
        {$this->_getAnlassCases()}                                                    AS anlass_case,

        IF(
            sit.anlass LIKE 'r%' AND MIN(h.datum) IS NULL,
            IF(sit.start_date = '0000-00-00', sit.start_date_rezidiv, sit.start_date),
            MIN(h.datum)
        )                                                                             AS bezugsdatum,

        sit.diagnose                                                                  AS diagnose,

        IF(sit.diagnose LIKE 'C43%' AND (
            (sit.morphologie != '8247/3' AND
            sit.morphologie != '9120/3' AND
            sit.morphologie != '8832/3' AND
            sit.morphologie != '8833/3') OR
            sit.morphologie IS NULL),
            1,
            0
        )                                                                      AS invasives_malignom,

        IF(
            sit.diagnose LIKE 'C44%' AND
            (sit.morphologie LIKE '805_/3' OR
             sit.morphologie LIKE '806_/3' OR
             sit.morphologie LIKE '807_/3' OR
             sit.morphologie LIKE '808_/3' OR
             sit.morphologie LIKE '809_/3' OR
             sit.morphologie LIKE '810_/3' OR
             sit.morphologie LIKE '811_/3'
            ),
            1,
            0
        )                                                                             AS epithelialer_tumor,

        -- wird unten nochmal nachvearbeitet
        IF(
            sit.morphologie = '8247/3' OR
            sit.morphologie = '9120/3' OR
            sit.morphologie = '8832/3' OR
            sit.morphologie = '8833/3',
            1,
            0
        )                                                                             AS seltene_tumore,

        sit.t                                                                         AS t,

        sit.pt                                                                        AS pt,

        sit.n                                                                         AS n,

        sit.cn                                                                        AS cn,

        sit.pn                                                                        AS pn,

        sit.m                                                                         AS m,

        sit.ajcc_prae                                                                 AS ajcc_prae,

        sit.ajcc                                                                      AS ajcc,

        sit.morphologie                                                               AS morphologie,

        sit.uvea                                                                      AS uvea,

        sit.konjunktiva                                                               AS konjunktiva,

        sit.schleimhaut                                                               AS schleimhaut,

        IF(COUNT(DISTINCT
            IF(s.form = 'untersuchung' AND LOCATE('C77', SUBSTRING(s.report_param, 12)) != 0,
                (SELECT
                    u.untersuchung_id
                FROM untersuchung u
                WHERE
                    u.untersuchung_id = s.form_id AND
                    (LOCATE('3-03', u.art) != 0 OR
                     LOCATE('3-e01.y', u.art) != 0 OR
                     LOCATE('3-e39.y', u.art) != 0)
                ),
                NULL
        )) > 0, 1, NULL)                                                              AS lk_sonographie,

        IF(
            COUNT(DISTINCT IF(tp.grundlage = 'tk', tp.therapieplan_id, NULL)) OR
            COUNT(DISTINCT IF(s.form = 'konferenz_patient' AND SUBSTRING(s.report_param, 6) != '' AND
                SUBSTRING(s.report_param, 6) BETWEEN sit.start_date AND sit.end_date, s.form_id, NULL)
            ), 1, NULL
        )                                                                             AS tumorkonferenz,

        IF(COUNT(DISTINCT IF(s.form = 'therapieplan_abweichung' AND
            s.form_date BETWEEN sit.start_date AND sit.end_date, s.form_id,
            NULL)),
        1, NULL)                                                                      AS therapieabweichung,

        IF(COUNT(DISTINCT IF(s.form = 'konferenz_patient' AND
            LEFT(s.report_param, 4) = 'morb' AND SUBSTRING(s.report_param, 6) != '' AND
            SUBSTRING(s.report_param, 6) BETWEEN sit.start_date AND sit.end_date, s.form_id, NULL)
        ), 1, NULL)                                                                   AS morbiditaetskonferenz,

        GROUP_CONCAT(DISTINCT
               IF(s.form = 'studie',
                   CONCAT_WS('|', s.form_id, DATE_FORMAT(s.report_param, '%d.%m.%Y')),
                   NULL
               )
               SEPARATOR ', '
            )                                                                         AS 'datum_studie',

         COUNT(DISTINCT IF(s.form = 'studie', s.form_id, NULL))                       AS 'anzahl_studienteilnahme',

        MIN(IF(h.art = 'pr', h.datum, null))                                          AS datum_prae_histo,

        MIN(IF(h.art = 'po', h.datum, null))                                          AS datum_post_histo,

        COUNT(DISTINCT h.histologie_id)                                               AS anz_histo,

        COUNT(DISTINCT IF(
            h.lk_bef IS NOT NULL OR
            h.lk_inguinal_bef IS NOT NULL OR
            h.lk_iliakal_bef IS NOT NULL OR
            h.lk_axillaer_bef IS NOT NULL OR
            h.lk_zervikal_bef IS NOT NULL,
            h.histologie_id,
            NULL
            )
        )                                                                             AS anz_histo_lk_untersuchung,

        COUNT(DISTINCT IF(
            (h.lk_entf IS NOT NULL AND h.lk_entf > 0) OR
            (h.lk_inguinal_entf IS NOT NULL AND h.lk_inguinal_entf > 0) OR
            (h.lk_iliakal_entf IS NOT NULL AND h.lk_iliakal_entf > 0) OR
            (h.lk_axillaer_entf IS NOT NULL AND h.lk_axillaer_entf > 0) OR
            (h.lk_zervikal_entf IS NOT NULL AND h.lk_zervikal_entf > 0),
            h.histologie_id,
            NULL
            )
        )                                                                             AS anz_histo_lk_entfernt,

        IF(COUNT(IF(
                s.form = 'histologie' AND s.form_id IN ({$this->_getPreSelect('breslowkl2')}),
                1,
                NULL)
            ) > 0,
            1,
            NULL
        )                                                                            AS tumordicke_kl2mm,

        IF(COUNT(IF(
                s.form = 'histologie' AND s.form_id IN ({$this->_getPreSelect('breslowgr2')}),
                1,
                NULL)
            ) > 0,
            1,
            NULL
        )                                                                            AS tumordicke_gr2mm,

        IF(COUNT(IF(
                s.form = 'histologie' AND s.form_id IN ({$this->_getPreSelect('breslowgr1')}),
                1,
                NULL)
            ) > 0,
            1,
            NULL
        )                                                                            AS tumordicke_gr1mm,

        IF(COUNT(h.braf),
            IF(COUNT(IF(h.braf = 'mut', h.histologie_id, NULL)),
                1,
                0
            ),
            NULL
        )                                                                             AS braf,

        IF(
            MAX(sit.resektionsrand) IS NULL,
            NULL,
            IF(MAX(sit.resektionsrand) = 0, 0, 1)
        )                                                                             AS randkontrolle_durchgef,

        MAX(sit.resektionsrand)                                                       AS sicherheitsabstand,

        IF(COUNT(DISTINCT IF(
               th_sys.vorlage_therapie_art IN ('c','ci','cst'),
               th_sys.therapie_systemisch_id,
               NULL
           )) OR
           COUNT(DISTINCT IF(
               th_str.vorlage_therapie_art IN ('cst'),
               th_str.strahlentherapie_id,
               NULL
           )),
        1,
        NULL)                                                                           AS 'chemotherapie',

        IF(
            COUNT(DISTINCT IF(
                th_sys.vorlage_therapie_id IN ({$this->_getPreSelect('dacarbazinChemo')}) AND th_sys.intention = 'kura',
                th_sys.therapie_systemisch_id,
                NULL
            )) OR
            COUNT(DISTINCT IF(
                th_str.vorlage_therapie_id IN ({$this->_getPreSelect('dacarbazinChemo')}) AND th_str.intention = 'kura',
                th_str.strahlentherapie_id,
                NULL
            ))
            , 1, NULL
        )                                                                               AS 'adj_sys_chemo_dacarbazin',

        IF(COUNT(th_sys.therapie_systemisch_id) > 0,
            IF(COUNT(IF(th_sys.therapieform = 'perf', 1, NULL)) > 0,
                1,
                IF(COUNT(IF(th_sys.therapieform IS NOT NULL AND th_sys.therapieform != 'perf', 1, NULL)) > 0,
                    0,
                    NULL
                )
            ),
            NULL
        )                                                                               AS 'adj_extperfusion',

        IF(
            COUNT(DISTINCT IF(
                th_sys.vorlage_therapie_id IN ({$this->_getPreSelect('brafIndikator')}),
                th_sys.therapie_systemisch_id,
                NULL
            )) OR
            COUNT(DISTINCT IF(
                th_str.vorlage_therapie_id IN ({$this->_getPreSelect('brafIndikator')}),
                th_str.strahlentherapie_id,
                NULL
            ))
            , 1, NULL
        )                                                                               AS 'braf_therapie',

        IF(COUNT(
            DISTINCT IF(
               th_sys.vorlage_therapie_art IN ('ah','ahst'),
               th_sys.therapie_systemisch_id,
               NULL
            )) OR
           COUNT(DISTINCT IF(
               th_str.vorlage_therapie_art IN ('ahst'),
               th_str.strahlentherapie_id,
               NULL
           ))
         , 1, NULL)                                                                  AS antih_th,

        IF(COUNT(
            DISTINCT IF(
               th_sys.vorlage_therapie_art IN ('ist','ci','i'),
               th_sys.therapie_systemisch_id,
               NULL
            )) OR
           COUNT(DISTINCT IF(
               th_str.vorlage_therapie_art IN ('ist'),
               th_str.strahlentherapie_id,
               NULL
           ))
         , 1, NULL)                                                                  AS 'immuntherapie',


        IF(COUNT(th_str.strahlentherapie_id) > 0, 1, NULL)                            AS 'strahlentherapie',

        IF(MAX(IF(th_str.ziel_lymph IS NOT NULL, th_str.beginn, NULL)) >
           MAX(IF(op.art_primaertumor IS NOT NULL, op.datum, NULL)),
           1,
           NULL
        )                                                                             AS 'postop_strahlentherapie',

        IF(COUNT(DISTINCT
            IF(s.form = 'sonstige_therapie',
                s.form_id,
                NULL)
            ) > 0,
            1,
            NULL
        )                                                                             AS 'sonstige_therapie',

        COUNT(DISTINCT IF(th_sys.vorlage_therapie_art IN ('c','ci','cst'), th_sys.therapie_systemisch_id, NULL)) +
        COUNT(DISTINCT IF(th_str.vorlage_therapie_art IN ('cst'), th_str.strahlentherapie_id, NULL))               AS chemo_count,

        COUNT(DISTINCT th_sys.therapie_systemisch_id)                                     AS 'th_sys_count',

        IF(COUNT(IF(s.form = 'labor', 1, NULL)),
            IF(COUNT(IF(s.form = 'labor' AND s.form_id IN ({$this->_getPreSelect('ldhIds')}), 1, NULL)),
                1,
                0
            ),
            NULL
        )                                                                                AS 'ldh',

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
         )                                                                            AS psychoonk_betreuung,

        MAX(DISTINCT b.sozialdienst)                                                  AS sozialdienst,

        IF(COUNT(IF(s.form = 'fragebogen' AND s.report_param IN ({$this->_getPreSelect('zufrFragebogen')}), s.form_id, NULL)),1, NULL) AS 'patientenbefragung',

        MAX(IF(s.form = 'nachsorge' AND LOCATE(CONCAT_WS('','-',sit.erkrankung_id,'-'), s.report_param) > 0, s.form_date, NULL)) AS letzte_nachsorge,

        sit.rezidiv_datum                                                             AS letztes_rezidiv_progress,

        MAX(x.todesdatum)                                                             AS todesdatum,

         IF(
            MAX(x.tod_tumorassoziation) IS NOT NULL AND MAX(x.tod_tumorassoziation) IN ('tott', 'totn'),
            1,
            IF(
               MAX(x.tod_tumorassoziation) IS NOT NULL AND MAX(x.tod_tumorassoziation) NOT IN ('tott', 'totn'),
               0,
               NULL
            )
        )                                                                             AS 'tod_tumorbedingt',

         CONCAT_WS('|',
            MIN(IF(s.form = 'sonstige_therapie', s.form_date, NULL)),
            MIN(th_sys.beginn),
            MIN(th_str.beginn),
            MIN(IF('1' IN (op.art_primaertumor, op.art_lk,op.art_metastasen,op.art_rezidiv,op.art_nachresektion,op.art_revision), op.datum, NULL)),
            '9999-12-31'
         )                                                                       AS 'max_ajcc',

         MIN(
             IF(
                 '1' IN (
                    op.art_primaertumor,
                    op.art_lk,
                    op.art_metastasen,
                    op.art_rezidiv,
                    op.art_nachresektion,
                    op.art_revision),
                op.datum,
                '9999-12-31'
            )
         )                                                                       AS 'min_ajcc',

         sit.anlass,
         sit.patient_id,
         sit.erkrankung_id,
         sit.start_date,
         sit.end_date
      FROM ($preQuery) sit
         {$this->_innerStatus()}
         {$this->_statusJoin('histologie h')}
         {$this->_statusJoin('beratung b')}
         {$this->_statusJoin('eingriff op')}
         {$this->_statusJoin('therapieplan tp')}
         {$this->_statusJoin('therapie_systemisch th_sys')}
         {$this->_statusJoin('strahlentherapie th_str')}
         {$this->_statusJoin('abschluss x')}

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

    foreach ($data as $i => $dataset) {
        unset($data[$i]['relevant']);

        $case = $dataset['anlass'];
        $diseaseId = $dataset['erkrankung_id'];

        $data[$i]['datum_studie'] = $this->_removeIdentifier($dataset['datum_studie']);

        $primaryCase = array_key_exists($diseaseId . $case, $primaryCases) === true ? $primaryCases[$diseaseId . $case] : null;

        if ($primaryCase === null && str_starts_with($case, 'r') === true) {
            $primaryCase = 0;
        }

        $data[$i]['primaerfall'] = $primaryCase;

        if ($dataset['seltene_tumore'] == 0) {
            if (str_starts_with($dataset['diagnose'], 'C') === true && $dataset['invasives_malignom'] == 0 && $dataset['epithelialer_tumor'] == 0) {
                $data[$i]['seltene_tumore'] = '1';
            }
        }

        //AJCC Berechnung
        $data[$i]['ajcc_prae'] = $stageCalc->calcToMaxDate($dataset['ajcc_prae'], min(explode('|', $dataset['max_ajcc'])));
        $data[$i]['ajcc']      = $stageCalc->calc($dataset['ajcc']);
    }

?>
