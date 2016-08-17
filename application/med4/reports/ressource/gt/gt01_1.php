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

    $having = "(LEFT(diagnose,3) IN ('C48','C51','C52','C53','C54','C55','C56','C57','C58') OR
        diagnose = 'C79.82' OR (diagnose = 'D39.1' AND g = 'B'))
    ";
    $relevantSelectWhere = "ts.erkrankung_id = t.erkrankung_id AND ts.anlass = t.anlass";
    $rezidivOneStepCheck = $this->_rezidivOneStepCheck();
    $stageCalc = stageCalc::create($this->_db, $this->_params['sub']);
    $separator_row = "\x01";
    $separator_col = "\x02";

    $tsHistoFields = implode(' IS NOT NULL AND ts.', array(
        'morphologie', 'g', 'l', 'v', 'ppn', 'm', 'figo'
    ));

    $relevantSelects = array(
        $stageCalc->select('c', 'figo')  . "AS 'figo_prae'",
        $stageCalc->select(null, 'figo') . "AS 'figo'",
        "
            (
                SELECT
                    IF(
                        MAX(ts.nur_zweitmeinung) IS NOT NULL OR
                        MAX(ts.nur_diagnosesicherung) IS NOT NULL OR
                        MAX(ts.kein_fall) IS NOT NULL,
                        1,
                        NULL
                    )
                FROM    tumorstatus ts
                WHERE {$relevantSelectWhere}
            ) AS 'nicht_zaehlen'
        ",
        "
            (
                SELECT      ts.t
                FROM        tumorstatus ts
                WHERE       {$relevantSelectWhere}
                AND         ts.t IS NOT NULL
                AND         ts.n IS NOT NULL
                AND         ts.m IS NOT NULL
                ORDER BY    ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC
                LIMIT 1
            ) AS t
        ",
        "
            (
                SELECT      ts.n
                FROM        tumorstatus ts
                WHERE       {$relevantSelectWhere}
                AND         ts.t IS NOT NULL
                AND         ts.n IS NOT NULL
                AND         ts.m IS NOT NULL
                ORDER BY    ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC
                LIMIT 1
            ) AS n
        ",
        "
            (
                SELECT      ts.m
                FROM        tumorstatus ts
                WHERE       {$relevantSelectWhere}
                AND         ts.t IS NOT NULL
                AND         ts.n IS NOT NULL
                AND         ts.m IS NOT NULL
                ORDER BY    ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC
                LIMIT 1
            ) AS m
        ",
        "
            (
                SELECT      ts.g
                FROM        tumorstatus ts
                WHERE       {$relevantSelectWhere}
                AND         ts.g IS NOT NULL
                ORDER BY    ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC
                LIMIT 1
            ) AS g
        ",
        "(SELECT ts.l FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.l IS NOT NULL ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS l",
        "(SELECT ts.v FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.v IS NOT NULL ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS v",
        "(SELECT ts.ppn FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.ppn IS NOT NULL ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS ppn",
        "(SELECT ts.resektionsrand FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.resektionsrand IS NOT NULL ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS resektionsrand",
        "(SELECT MAX(ts.lk_staging) FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.lk_staging IS NOT NULL ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS lk_staging",
        "(SELECT t FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.{$tsHistoFields} IS NOT NULL AND LEFT(ts.t, 1) = 'p' AND LEFT(ts.n, 1) = 'p' ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS ts_vollst_histo",
        "
            (
                SELECT      ts.r_lokal
                FROM        tumorstatus ts
                WHERE       {$relevantSelectWhere}
                AND         ts.r_lokal IS NOT NULL
                ORDER BY    ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC
                LIMIT 1
            ) AS r_lokal
        ",
        "
            (
                SELECT      ts.datum_sicherung
                FROM        tumorstatus ts
                WHERE       {$rezidivOneStepCheck}
                AND         ts.rezidiv_lokal IS NOT NULL
                ORDER BY    ts.datum_sicherung ASC
                LIMIT 1
            ) AS rezidiv_lokal_datum
        ",
        "
            (
                SELECT      ts.datum_sicherung
                FROM        tumorstatus ts
                WHERE       {$rezidivOneStepCheck}
                AND         ts.rezidiv_lk IS NOT NULL
                ORDER BY    ts.datum_sicherung ASC LIMIT 1
            ) AS rezidiv_lk_datum
        ",
        "
            (
                SELECT      ts.datum_sicherung
                FROM        tumorstatus ts
                WHERE       {$rezidivOneStepCheck}
                AND         ts.rezidiv_metastasen IS NOT NULL
                ORDER BY    ts.datum_sicherung ASC LIMIT 1
            ) AS rezidiv_metastasen_datum
        ",
        "
            IFNULL(
                (SELECT      1
                FROM        tumorstatus ts
                WHERE       {$relevantSelectWhere}
                AND         LEFT(ts.anlass, 1) = 'r'
                AND         (ts.rezidiv_lk IS NOT NULL OR ts.rezidiv_lokal IS NOT NULL)
                ORDER BY    ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC
                LIMIT 1
            ), 0) AS lokalrezidiv
        ",
    );

    $codings = array();

    foreach (sql_query_array($this->_db, "SELECT code, bez FROM l_basic WHERE klasse = 'tumorverhalten'") as $row) {
        $codings[$row['code']] = $row['bez'];
    }

    $gynUser = dlookup($this->_db,
        'user',
        'IFNULL(GROUP_CONCAT(DISTINCT user_id), 0)',
        "fachabteilung IN ('fronk', 'gynonk')"
    );

    $platinIds = dlookup($this->_db,
        'vorlage_therapie_wirkstoff',
        'IFNULL(GROUP_CONCAT(DISTINCT vorlage_therapie_id), 0)',
        "(wirkstoff IN ('carboplatin', 'cisplatin', 'oxaliplatin'))"
    );

    $platinIds = strlen($platinIds) > 0 ? explode(',', $platinIds) : array();

    $cisplatinIds = dlookup($this->_db,
        'vorlage_therapie_wirkstoff',
        'IFNULL(GROUP_CONCAT(DISTINCT vorlage_therapie_id), 0)',
        "(wirkstoff = 'cisplatin')"
    );

    $cisplatinIds = strlen($cisplatinIds) > 0 ? explode(',', $cisplatinIds) : array();

    $firstLineA = dlookup($this->_db,
        'vorlage_therapie_wirkstoff',
        'IFNULL(GROUP_CONCAT(DISTINCT vorlage_therapie_id), 0)',
        "(wirkstoff = 'carboplatin' AND dosis = '5' AND einheit = 'auc' AND zyklus_anzahl = '6')"
    );

    $firstLine = '';
    if ($firstLineA !== '0') {
        $firstLine = dlookup($this->_db,
            'vorlage_therapie_wirkstoff',
            'IFNULL(
                GROUP_CONCAT(DISTINCT vorlage_therapie_id),
                0)',
            "(wirkstoff = 'paclitaxel'
                AND dosis = '175'
                AND einheit = 'mgm2'
                AND zyklus_anzahl = '6'
                AND vorlage_therapie_id IN (" . $firstLineA . ")
            )"
        );
    }

    // needs extra query for 'therapieverhalten_platin' (out of range: date and case)
    $tumorBehaviors = sql_query_array($this->_db, "
        SELECT
            sys.therapie_systemisch_id,
            sys.patient_id,
            sys.erkrankung_id,
            sys.tumorverhalten_platin,
            sys.beginn,
            sys.ende,
            sys.vorlage_therapie_id
        FROM erkrankung erk
            INNER JOIN therapie_systemisch sys ON sys.erkrankung_id = erk.erkrankung_id AND
                                                  sys.tumorverhalten_platin IS NOT NULL
        WHERE
            erk.erkrankung = 'gt'
        GROUP BY
            sys.therapie_systemisch_id
        ORDER BY
            sys.erkrankung_id,
            sys.beginn
    ");

    // rearrange for faster indexing
    $tmp = array();

    foreach ($tumorBehaviors as $tumorBehavior) {
        $tmp[$tumorBehavior['erkrankung_id']][] = $tumorBehavior;
    }

    $tumorBehaviors = $tmp;

    // preselect all mono-therapies
    $monotherapy = sql_query_array($this->_db,
        "SELECT
            vorlage_therapie_id,
            IFNULL(wirkstoff, '')     AS wirkstoff,
            IFNULL(zyklustag, '0')    AS day0,
            IFNULL(zyklustag02, '0')  AS day1,
            IFNULL(zyklustag03, '0')  AS day2,
            IFNULL(zyklustag04, '0')  AS day3,
            IFNULL(zyklustag05, '0')  AS day4,
            IFNULL(zyklustag06, '0')  AS day5,
            IFNULL(zyklustag07, '0')  AS day6,
            IFNULL(zyklustag08, '0')  AS day7,
            IFNULL(zyklustag09, '0')  AS day8,
            IFNULL(zyklustag10, '0')  AS day9
        FROM vorlage_therapie_wirkstoff
        WHERE wirkstoff IN ('doxorubicinpeg', 'topotecan', 'gemcitabin', 'paclitaxel') AND
        vorlage_therapie_id IN (SELECT vtw.vorlage_therapie_id
      FROM vorlage_therapie_wirkstoff vtw
      GROUP BY vtw.vorlage_therapie_id
      HAVING COUNT(vtw.vorlage_therapie_id) < 2)
    ");

    // post processing -> filter
    $monoIds  = array();

    foreach ($monotherapy as $mono) {
        $noMonoId = '';
        if ($mono['wirkstoff'] === 'paclitaxel') {
            for ($currentDay = 0; $currentDay <= 8; $currentDay++) {
                $nextDay = $currentDay + 1;
                if ($mono['day0'] == 0 || $mono['day1'] == 0) {
                    $noMonoId = $mono['vorlage_therapie_id'];
                    break;
                }

                if ($mono['day' . $currentDay] != 0 && $mono['day' . $nextDay] != 0) {
                    if ($mono['day' . $nextDay] - $mono['day' . $currentDay] !== 7) {
                        $noMonoId = $mono['vorlage_therapie_id'];
                    }
                }
            }
            if (strlen($noMonoId) === 0) {
                $monoIds[] = $mono['vorlage_therapie_id'];
            }
        } else {
            $monoIds[] = $mono['vorlage_therapie_id'];
        }
    }

    // preselect all combi-therapies
    $combitherapy = sql_query_array($this->_db,
        "SELECT
            vorlage_therapie_id,
            IFNULL(wirkstoff, '') AS wirkstoff
        FROM vorlage_therapie_wirkstoff
        WHERE wirkstoff IN ('carboplatin', 'gemcitabin', 'bevacizumab', 'doxorubicinpeg', 'paclitaxel')
    ");

    $tmp = array();

    foreach ($combitherapy as $combi) {
        $tmp[$combi['vorlage_therapie_id']][] = $combi['wirkstoff'];
    }

    $combitherapy = $tmp;

    $preQuery = $this->_getPreQuery($having, array_merge($relevantSelects, $additionalTsSelects));

    // Bevacizumab
    $primaryBevacizumabId = array();

    $primaryCombyTherapyQuery = "
        SELECT
            sit.erkrankung_id,
            IF(sit.anlass = 'p', 1, 0) AS 'primaerfall',
            GROUP_CONCAT(DISTINCT
                IF(th_sys.therapie_systemisch_id IS NOT NULL,
                    CONCAT_WS('{$separator_col}',
                        IFNULL(th_sys.therapie_systemisch_id, ''),
                        IFNULL(th_sys.vorlage_therapie_id, ''),
                        IFNULL(th_sys.beginn, ''),
                        IFNULL(th_sys.vorlage_therapie_art, ''),
                        IFNULL(th_sys.therapielinie, '' ),
                        IFNULL(th_sys.studie, '' )
                    ),
                    NULL
                )
                SEPARATOR '{$separator_row}'
            ) AS 'therapien_systemisch'
        FROM ($preQuery) sit
          {$this->_innerStatus()}

          LEFT JOIN therapie_systemisch th_sys ON s.form = 'therapie_systemisch' AND
                                                  th_sys.therapie_systemisch_id = s.form_id
        WHERE
            {$this->_getNcState()}
        GROUP BY
            sit.patient_id,
            sit.erkrankung_id,
            sit.anlass
        HAVING
            primaerfall = 1
    ";

    foreach (sql_query_array($this->_db, $primaryCombyTherapyQuery) as $record) {
        $therapySystemic = $this->recordStringToArray($record['therapien_systemisch'], array(
            "therapie_systemisch_id",
            "vorlage_therapie_id",
            "beginn",
            "vorlage_therapie_art",
            "therapielinie",
            "studie"
        ));

        foreach ($therapySystemic as $systemic) {
            if (str_starts_with($systemic['vorlage_therapie_art'], 'c') === true) {
                $vtId = $systemic['vorlage_therapie_id'];

                if (array_key_exists($vtId, $combitherapy) === true) {
                    $vtCombi = $combitherapy[$vtId];

                    $diseaseId = $record['erkrankung_id'];

                    if (in_array('bevacizumab', $vtCombi) === true) {
                        $primaryBevacizumabId[] = $diseaseId;
                    }
                }
            }
        }
    }

    // main query
    $query = "SELECT
        {$additionalFields}
        sit.nachname               AS 'nachname',
        sit.vorname                AS 'vorname',
        sit.geburtsdatum           AS 'geburtsdatum',
        sit.patient_nr             AS 'patient_nr',
        IF(sit.anlass = 'p', 1, 0) AS 'primaerfall',
        {$this->_getAnlassCases()} AS 'anlass_case',
        sit.datum_sicherung        AS 'datum_sicherung',

        IF(
            sit.anlass LIKE 'r%' AND MIN(h.datum) IS NULL,
            IF(
                sit.start_date = '0000-00-00',
                sit.start_date_rezidiv,
                sit.start_date
            ),
            MIN(h.datum)
        ) AS 'bezugsdatum',

        sit.diagnose AS 'diagnose',
        sit.morphologie AS 'morphologie',

        IF(
            COUNT(
                DISTINCT IF(
                    tp.grundlage = 'tk' AND
                    tp.zeitpunkt = 'prae',
                    tp.therapieplan_id,
                    NULL
                )
            ) OR COUNT(
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
        ) AS 'praeop_konferenz',

        IF(
            COUNT(
                DISTINCT IF(
                    tp.grundlage = 'tk' AND
                    tp.zeitpunkt = 'post',
                    tp.therapieplan_id,
                    NULL
                )
            ) OR COUNT(
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
        ) AS 'postop_konferenz',

        MAX(
            IF(
                1 IN (op.art_primaertumor, op.art_rezidiv),
                op.datum,
                NULL
            )
        ) AS 'datumprimaer_rezidiv_op',

        MAX(
            IF(
                1 IN (op.art_primaertumor, op.art_rezidiv),
                op.eingriff_id,
                NULL
            )
        ) AS 'primaerop_eingriff_id',

        MAX(
            IF(
                1 IN (op.art_primaertumor, op.art_rezidiv),
                (SELECT
                    l.bez AS 'bez'
                FROM
                    l_basic l
                WHERE
                    l.klasse = 'tumorrest_groesse' AND l.code = op.tumorrest_groesse
                ),
            NULL)
        ) AS 'tumorrest',

        sit.r        AS 'r',
        sit.r_lokal  AS 'r_lokal',

        IF(
            COUNT(DISTINCT
                IF(
                    komp.revisionsoperation = '1',
                    komp.komplikation_id,
                    NULL
                )
            ) OR COUNT(DISTINCT
                IF(
                    op.art_revision = '1',
                    op.eingriff_id,
                    NULL
                )
            ),
            1,
            IF(
                COUNT(DISTINCT komp.komplikation_id) > 0 AND MAX(komp.revisionsoperation) = 0,
                0,
                NULL
            )
        ) AS 'revisions_op',

         COUNT(DISTINCT
             IF('1' IN (
                 op.art_primaertumor,
                 op.art_lk,
                 op.art_metastasen,
                 op.art_rezidiv,
                 op.art_nachresektion,
                 op.art_revision),
                 op.eingriff_id,
             NULL
             )
         ) AS 'anz_ops',

         sit.pt AS 'pt',
         sit.pn AS 'pn',
         sit.m  AS 'm',

         ## Beachten: Figo wird am Ende nochmals nachverarbeitet! ##
         sit.figo_prae AS 'figo_prae',
         null          AS 'figo_nach_neoadj_th',
         sit.figo      AS 'figo',

         IF(sit.g IS NOT NULL, (SELECT bez FROM l_basic WHERE klasse = 'g' AND code = sit.g), NULL ) AS 'g',

         sit.l as 'l',
         sit.v as 'v',
         sit.ppn as 'ppn',

         IF(COUNT(
            DISTINCT IF(
               tp.ah = '1' AND
               tp.ah_intention IN ('kura', 'pala'),
               tp.therapieplan_id,
               NULL
            )
         ), 1, IF(MAX(tp.ah) = 0, 0, NULL)) AS 'adj_antihormonelleth_gepl',

         IF(COUNT(
            DISTINCT IF(
               tp.chemo = '1' AND
               tp.chemo_intention IN ('kura', 'pala'),
               tp.therapieplan_id,
               NULL
            )
         ), 1, IF(MAX(tp.chemo) = 0, 0, NULL)) AS 'adj_chemoth_gepl',

         IF(COUNT(
            DISTINCT IF(
               tp.immun = '1' AND
               tp.immun_intention IN ('kura', 'pala'),
               tp.therapieplan_id,
               NULL
            )
         ), 1, IF(MAX(tp.immun) = 0, 0, NULL)) AS 'adj_immunth_gepl',

         IF(COUNT(
            DISTINCT IF(
               tp.strahlen = '1' AND
               tp.strahlen_intention IN ('kura', 'pala'),
               tp.therapieplan_id,
               NULL
            )
         ), 1, IF(MAX(tp.strahlen) = 0, 0, NULL)) AS 'adj_strahlenth_gepl',

         IF(COUNT(
            DISTINCT IF(
               th_sys.vorlage_therapie_art IN ('ah','ahst') AND
               th_sys.intention IN ('kura', 'pala'),
               th_sys.therapie_systemisch_id,
               NULL
            )
         ), 1, NULL) AS 'adj_antihormonelleth_durchgef',

         IF(COUNT(
            DISTINCT IF(
               th_sys.vorlage_therapie_art IN ('c','ci','cst') AND
               th_sys.intention IN ('kura', 'pala'),
               th_sys.therapie_systemisch_id,
               NULL
            )
         ), 1, NULL) AS 'adj_chemoth_durchgef',

         IF(COUNT(
            DISTINCT IF(
               th_sys.vorlage_therapie_art IN ('ist','ci','i') AND
               th_sys.intention IN ('kura', 'pala'),
               th_sys.therapie_systemisch_id,
               NULL
            )
         ), 1, NULL) AS 'adj_immunth_durchgef',

         IF(COUNT(
            DISTINCT IF(
               th_str.intention IN ('kura', 'pala'),
               th_str.strahlentherapie_id,
               NULL
            )
         ), 1, NULL) AS 'adj_strahlenth_durchgef',

         MAX(tp.strahlen_indiziert) AS 'strahlentherapieindiziert',

         IF(
             MIN(cis_str.zyklus_beginn)  <= MAX(cis_zyk.zyklus_anzahl)  OR
             MIN(cis_str2.zyklus_beginn) <= MAX(cis_zyk2.zyklus_anzahl) OR
             MIN(cis_zyk.zyklus_beginn)  <= MAX(cis_str.zyklus_anzahl)  OR
             MIN(cis_zyk2.zyklus_beginn) <= MAX(cis_str2.zyklus_anzahl),
             1,
             NULL
         ) AS 'sim_radio_chemo_cisplatin',

         GROUP_CONCAT(DISTINCT
               IF(s.form = 'studie',
                   CONCAT_WS('|', s.form_id, DATE_FORMAT(s.report_param, '%d.%m.%Y')),
                   NULL
               )
            SEPARATOR ', '
         ) AS 'datum_studie',

         COUNT(DISTINCT IF(s.form = 'studie', s.form_id, NULL)
         ) AS 'count_studie',

         MAX(DISTINCT
              IF(
                  b.psychoonkologie IS NOT NULL,
                  IF(
                      b.psychoonkologie = 0,
                      b.psychoonkologie,
                      IF (b.psychoonkologie_dauer >= 20, 1, 0)
                  ),
              NULL
              )
         ) AS 'psychoonk_betreuung',

         MAX(DISTINCT b.sozialdienst) AS 'betreuungsozialdienst',

         IF(
            COUNT(
                IF(s.form = 'fragebogen' AND s.report_param IN ({$this->_getPreSelect('zufrFragebogen')}), s.form_id,
                    NULL
                )
            ),1, NULL

         ) AS 'befragung',

         MAX(n.datum) AS 'datumletztenachsorge',

         IF(sit.rezidiv_lokal_datum IS NOT NULL AND sit.rezidiv_lk_datum IS NOT NULL,
            IF(sit.rezidiv_lokal_datum < sit.rezidiv_lk_datum,
               sit.rezidiv_lokal_datum,
               sit.rezidiv_lk_datum
            ),
            IFNULL(sit.rezidiv_lokal_datum, sit.rezidiv_lk_datum)
        ) AS 'datumlokalrezidiv',

        sit.rezidiv_metastasen_datum AS 'datummetastase',

        IF(MAX(x.abschluss_grund) IS NOT NULL AND MAX(x.abschluss_grund) = 'lost', 1,
            IF(MAX(x.abschluss_grund) IS NOT NULL AND MAX(x.abschluss_grund) != 'lost', 0, NULL)
        ) AS 'losttofu',

        MAX(x.todesdatum) AS 'todesdatum',

        MAX(
            IF(x.tod_tumorassoziation IS NOT NULL,
                IF(x.tod_tumorassoziation IN ('tott', 'totn'), 1, 0),
                NULL
            )
        ) AS 'tod_tumorbedingt',

        CONCAT_WS('{$separator_row}',
            MIN(IF(s.form = 'sonstige_therapie', s.form_date, NULL)),
            MIN(th_sys.beginn),
            MIN(th_str.beginn),
            MIN(IF('1' IN (
                op.art_primaertumor,
                op.art_lk,
                op.art_metastasen,
                op.art_rezidiv,
                op.art_nachresektion,
                op.art_revision),
                op.datum,
                NULL)
            ),
            '9999-12-31'
        ) AS 'max_figo',

        GROUP_CONCAT(DISTINCT IF(op.art_staging IS NOT NULL, CONCAT_WS(',',
            IFNULL(op.art_primaertumor, 0),
            IFNULL(op.art_metastasen, 0),
            IFNULL(op.art_rezidiv, 0),
            IFNULL(op.art_nachresektion, 0),
            IFNULL(op.art_revision, 0),
            IFNULL(op.art_sonstige, 0)
        ), null) ORDER BY op.datum DESC SEPARATOR '|') as 'art_staging',

        GROUP_CONCAT(DISTINCT IF(
            s.form = 'eingriff',
            (SELECT
                GROUP_CONCAT(DISTINCT eo.prozedur, eo.prozedur_seite SEPARATOR '{$separator_row}')
            FROM eingriff_ops eo
            WHERE
                eo.eingriff_id = s.form_id
            GROUP BY
                eo.eingriff_id
            ),
            NULL
            )
            SEPARATOR '{$separator_row}'
        ) as 'opscodes',

        GROUP_CONCAT(DISTINCT u.art, u.art_seite SEPARATOR '{$separator_row}') as 'opsCodesFromExaminaion',

        GROUP_CONCAT(DISTINCT op.laparotomie SEPARATOR '{$separator_row}')         AS 'laparotomie',
        GROUP_CONCAT(DISTINCT op.peritonealzytologie SEPARATOR '{$separator_row}') AS 'peritonealzytologie',
        GROUP_CONCAT(DISTINCT op.peritonealbiopsie SEPARATOR '{$separator_row}')   AS 'peritonealbiopsie',
        GROUP_CONCAT(DISTINCT op.adnexexstirpation SEPARATOR '{$separator_row}')   AS 'adnexexstirpation',
        GROUP_CONCAT(DISTINCT op.hysterektomie SEPARATOR '{$separator_row}')       AS 'hysterektomie',
        GROUP_CONCAT(DISTINCT op.omentektomie SEPARATOR '{$separator_row}')        AS 'omentektomie',
        GROUP_CONCAT(DISTINCT op.lymphonodektomie SEPARATOR '{$separator_row}')    AS 'lymphonodektomie',

        COUNT(IF(
            h.lk_pelvin_entf IS NOT NULL OR
            h.lk_pelvin_externa_l_entf IS NOT NULL OR
            h.lk_pelvin_interna_l_entf IS NOT NULL OR
            h.lk_pelvin_fossa_l_entf IS NOT NULL OR
            h.lk_pelvin_communis_l_entf IS NOT NULL OR
            h.lk_pelvin_externa_r_entf IS NOT NULL OR
            h.lk_pelvin_interna_r_entf IS NOT NULL OR
            h.lk_pelvin_fossa_r_entf IS NOT NULL OR
            h.lk_pelvin_communis_r_entf IS NOT NULL,
            1,
            NULL
        )) AS 'lk_pelvin',

        COUNT(IF(
            h.lk_para_entf IS NOT NULL OR
            h.lk_para_paracaval_entf IS NOT NULL OR
            h.lk_para_interaortocaval_entf IS NOT NULL OR
            h.lk_para_cranial_ami_entf IS NOT NULL OR
            h.lk_para_caudal_ami_entf IS NOT NULL OR
            h.lk_para_cranial_vr_entf IS NOT NULL,
            1,
            NULL
        )) AS 'lk_para',

        -- wird unten nochmal nachgearbeitet
        1 AS 'op_staging',

        IF(
            COUNT(
                DISTINCT IF(
                    komp.komplikation = 'Tueinris' AND komp.zeitpunkt = 'in',
                    komp.komplikation_id,
                    NULL
                )
            ),
            1, NULL
        ) AS 'tumorruptur',

        IF(
            COUNT(DISTINCT
                IF(
                    op.tumorrest = '0',
                    op.eingriff_id,
                    NULL
                )
            ) OR COUNT(DISTINCT
                IF(
                    sit.r_lokal = '0',
                    sit.tumorstatus_id,
                    NULL
                )
            ),
            1,
            NULL
        ) AS 'resektion',

        IF(
            COUNT(DISTINCT
                IF(
                    op.art_primaertumor = '1' AND op.operateur1_id IN ({$gynUser}),
                    op.eingriff_id,
                    NULL
                )
            ) OR COUNT(DISTINCT
                IF(
                    op.art_primaertumor = '1' AND op.operateur2_id IN ({$gynUser}),
                    op.eingriff_id,
                    NULL
                )
            ),
            1,
            NULL
        ) AS 'operative_therapie',

        GROUP_CONCAT(DISTINCT
            IF(th_sys.therapie_systemisch_id IS NOT NULL,
                CONCAT_WS('{$separator_col}',
                    IFNULL(th_sys.therapie_systemisch_id, ''),
                    IFNULL(th_sys.vorlage_therapie_id, ''),
                    IFNULL(th_sys.beginn, ''),
                    IFNULL(th_sys.vorlage_therapie_art, ''),
                    IFNULL(th_sys.therapielinie, '' ),
                    IFNULL(th_sys.studie, '' ),
                    IFNULL(th_sys.intention, '' )
                ),
                NULL
            )
            SEPARATOR '{$separator_row}'
        ) AS 'therapien_systemisch',

        IF(COUNT(
            DISTINCT IF(
               th_sys.vorlage_therapie_art IN ('c','ci','cst'),
               th_sys.therapie_systemisch_id,
               NULL
            )
        ), 1, null) AS 'durchgef_chemotherapie',

        NULL AS 'postop_chemotherapie',
        NULL AS 'platinhaltige_chemotherapie',
        NULL AS 'first_line_therapie',

        NULL AS 'encoded_tumorverhalten',
        NULL AS 'tumorverhalten',
        NULL AS 'enddatum',
        NULL AS 'monotherapie',
        0 AS 'combitherapie',

        IF(COUNT(
            DISTINCT IF(
               th_sys.vorlage_therapie_art IN ('c','ci','cst') AND (th_sys.studie IS NULL OR th_sys.studie != '1'),
               th_sys.therapie_systemisch_id,
               NULL
            )
        ), 1, 0) AS 'chemo_excl_study',

        '0' AS 'konisation',

        GROUP_CONCAT(DISTINCT
            IF(h.histologie_id IS NOT NULL AND h.eingriff_id IS NOT NULL,
                CONCAT_WS('{$separator_col}',
                    h.histologie_id,
                    h.eingriff_id,
                    h.datum,
                    IFNULL(h.konisation_exzision, ''),
                    IFNULL(h.konisation_x, ''),
                    IFNULL(h.konisation_y, ''),
                    IFNULL(h.konisation_z, ''),
                    IFNULL(h.invasionstiefe, ''),
                    IFNULL(h.invasionsbreite, ''),
                    IFNULL(h.groesse_x, ''),
                    IFNULL(h.groesse_y, ''),
                    IFNULL(h.groesse_z, ''),
                    IFNULL(h.lk_entf, ''),
                    IFNULL(h.lk_bef, ''),
                    IFNULL(h.lk_pelvin_entf, ''),
                    IFNULL(h.lk_pelvin_bef, ''),
                    IFNULL(h.lk_para_entf, ''),
                    IFNULL(h.lk_para_bef, ''),
                    IFNULL(h.lk_para_paracaval_entf, ''),
                    IFNULL(h.lk_para_interaortocaval_entf, ''),
                    IFNULL(h.lk_para_cranial_ami_entf, ''),
                    IFNULL(h.lk_para_caudal_ami_entf, ''),
                    IFNULL(h.lk_para_cranial_vr_entf, ''),
                    IFNULL(h.lk_para_paracaval_bef, ''),
                    IFNULL(h.lk_para_interaortocaval_bef, ''),
                    IFNULL(h.lk_para_cranial_ami_bef, ''),
                    IFNULL(h.lk_para_caudal_ami_bef, ''),
                    IFNULL(h.lk_para_cranial_vr_bef, ''),
                    IFNULL(h.groesste_ausdehnung, ''),
                    IFNULL(h.kapseldurchbruch, '')
                ),
                NULL
            )
            SEPARATOR '{$separator_row}'
        ) AS 'e_histologien',

        null AS 'ex_konisation',

        null AS 'ex_konisation_dimension',

        null AS 'invasionstiefe',

        null AS 'invasionsbreite',

        null AS 'gesamttgroesse',

        sit.resektionsrand AS 'sicherheitsabstand',

        sit.ts_vollst_histo,

        '0' AS 'vollst_histo',

        '0' AS 'lymphono',

        GROUP_CONCAT(DISTINCT IF(
            s.form = 'eingriff',
            (SELECT
                CONCAT_WS('{$separator_col}', eo.eingriff_id, eoe.datum)
            FROM eingriff_ops eo
                INNER JOIN eingriff eoe ON eo.eingriff_id = eoe.eingriff_id
            WHERE
                eo.eingriff_id = s.form_id AND (
                    LOCATE('5-401',   eo.prozedur) != 0 OR
                    LOCATE('5-402',   eo.prozedur) != 0 OR
                    LOCATE('5-404',   eo.prozedur) != 0 OR
                    LOCATE('5-406',   eo.prozedur) != 0 OR
                    LOCATE('5-407',   eo.prozedur) != 0
                )
            GROUP BY
                eo.eingriff_id
            ORDER BY
                eoe.datum
            LIMIT 1
            ),
            NULL
            )
            SEPARATOR '{$separator_row}'
        ) AS 'lymphono_op',

        null AS 'lk_ges_entf',
        null AS 'lk_ges_bef',

        null AS 'lk_pelvin_entf',
        null AS 'lk_pelvin_bef',

        null AS 'lk_para_entf',
        null AS 'lk_para_bef',

        null AS 'groesste_ausdehnung',
        null AS 'kapseldurchbruch',

        '0' AS 'vollst_lympho',

        sit.lk_staging AS 'lk_staging',

        null AS 'durchgef_radio_chemo',
        '0' AS 'durchgef_radio_chemo_cis',

        '0' AS 'radikale_hysterektomie',

        IF(COUNT(
            DISTINCT IF(
               th_sys.vorlage_therapie_art IN ('cst', 'st') AND
               th_sys.intention IN ('kura', 'pala'),
               th_sys.therapie_systemisch_id,
               NULL
            )
          )  OR
          COUNT(
            DISTINCT IF(
               th_str.vorlage_therapie_art IN ('cst', 'st') AND
               th_str.intention IN ('kura', 'pala'),
               th_str.strahlentherapie_id,
               NULL
            )
          )
         , 1, NULL) AS 'adj_radio_chemo',

        sit.lokalrezidiv AS 'lokalrezidiv',

        IF(
            MIN(IF(h.art = 'pr', h.datum, null)) < MIN(IFNULL(th_sys.beginn, '2050-01-01')) AND
            MIN(IF(h.art = 'pr', h.datum, null)) < MIN(IFNULL(th_str.beginn, '2050-01-01')) AND
            MIN(IF(h.art = 'pr', h.datum, null)) < MIN(IF(1 IN (op.art_primaertumor, op.art_rezidiv), op.datum, '2050-01-01')),
            1,
            0
        ) AS 'praeth_histo_sicherung',

        IF(
            COUNT(
                IF(
                    LOCATE('3-202', u.art) != 0 OR
                    LOCATE('3-222', u.art) != 0,
                    u.untersuchung_id,
                    null
                )
            ) &&
            COUNT(
                IF(
                    LOCATE('3-207', u.art) != 0 OR
                    LOCATE('3-225', u.art) != 0,
                    u.untersuchung_id,
                    null
                )
            ) &&
            COUNT(
                IF(
                    LOCATE('3-e48.y', u.art) != 0,
                    u.untersuchung_id,
                    null
                )
            )
        , 1, 0) AS 'bildg_diagnostik',

        '0' AS 'exenteration',

        sit.anlass,
        sit.start_date,
        sit.end_date,
        sit.erkrankung_id,
        sit.patient_id

    FROM ($preQuery) sit
        {$this->_innerStatus()}

        LEFT JOIN anamnese a                            ON s.form = 'anamnese' AND a.anamnese_id = s.form_id

        LEFT JOIN histologie h                          ON s.form = 'histologie' AND h.histologie_id  = s.form_id

        LEFT JOIN therapieplan tp                       ON s.form = 'therapieplan' AND tp.therapieplan_id = s.form_id

        LEFT JOIN therapie_systemisch th_sys            ON s.form = 'therapie_systemisch' AND
                                                           th_sys.therapie_systemisch_id = s.form_id

        LEFT JOIN vorlage_therapie vt                   ON vt.vorlage_therapie_id = th_sys.vorlage_therapie_id

        LEFT JOIN vorlage_therapie_wirkstoff cis_str    ON cis_str.vorlage_therapie_id = vt.vorlage_therapie_id AND
                                                           vt.art = 'cst' AND
                                                           cis_str.art = 'str'

        LEFT JOIN vorlage_therapie_wirkstoff cis_zyk    ON cis_zyk.vorlage_therapie_id = vt.vorlage_therapie_id AND
                                                           vt.art = 'cst' AND
                                                           cis_zyk.art = 'zyk' AND
                                                           cis_zyk.wirkstoff = 'cisplatin'

        LEFT JOIN strahlentherapie th_str               ON s.form = 'strahlentherapie' AND
                                                           th_str.strahlentherapie_id = s.form_id

        LEFT JOIN vorlage_therapie vt2                  ON vt2.vorlage_therapie_id = th_str.vorlage_therapie_id

        LEFT JOIN vorlage_therapie_wirkstoff cis_str2   ON cis_str2.vorlage_therapie_id = vt2.vorlage_therapie_id AND
                                                           vt2.art = 'cst' AND
                                                           cis_str2.art = 'str'

        LEFT JOIN vorlage_therapie_wirkstoff cis_zyk2   ON cis_zyk2.vorlage_therapie_id = vt2.vorlage_therapie_id AND
                                                           vt2.art = 'cst' AND
                                                           cis_zyk2.art = 'zyk' AND
                                                           cis_zyk2.wirkstoff = 'cisplatin'

        LEFT JOIN eingriff op                           ON s.form = 'eingriff' AND op.eingriff_id = s.form_id

        LEFT JOIN untersuchung u                        ON s.form = 'untersuchung' AND u.untersuchung_id = s.form_id

        LEFT JOIN komplikation komp                     ON s.form = 'komplikation' AND komp.komplikation_id = s.form_id

        LEFT JOIN beratung b                            ON s.form = 'beratung' AND b.beratung_id = s.form_id

        LEFT JOIN nachsorge n                           ON s.form = 'nachsorge' AND
                                                           LOCATE(CONCAT_WS('','-',sit.erkrankung_id,'-'), s.report_param) > 0 AND
                                                           n.nachsorge_id = s.form_id

        LEFT JOIN abschluss x                           ON s.form = 'abschluss' AND x.abschluss_id = s.form_id

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
        $data[$i]['datum_studie'] = $this->_removeIdentifier($dataset['datum_studie']);

        //Figo Berechnung
        $data[$i]['figo_prae']           = $stageCalc->calcToMaxDate($dataset['figo_prae'], min(explode($separator_row, $dataset['max_figo'])));
        $data[$i]['figo']                = $stageCalc->calc($dataset['figo']);
        $data[$i]['figo_nach_neoadj_th'] = $stageCalc->getCacheValue('tnm_praefix');

        // opsCodes
        $opsCodes = array_unique(array_merge(
            strlen($dataset['opscodes']) > 0               ? explode($separator_row, $dataset['opscodes']) : array(),
            strlen($dataset['opsCodesFromExaminaion']) > 0 ? explode($separator_row, $dataset['opsCodesFromExaminaion']) : array()
        ));

        unset($data[$i]['opsCodesFromExaminaion']);

        $parsedOpsCodes = array();

        foreach ($opsCodes as $opsCode) {
            $parsedOpsCodes[] = substr($opsCode, 0, -1);
        }

        $data[$i]['opscodes'] = implode(', ', $parsedOpsCodes);

        $adnexexstirpation = null;

        if (str_contains($dataset['adnexexstirpation'], 'B') === true || str_starts_with('5-653.30B', $opsCodes) === true) {
            $adnexexstirpation = '1';
        }

        $data[$i]['adnexexstirpation'] = $adnexexstirpation;


        $data[$i]['laparotomie'] = null;
        if (str_contains($dataset['laparotomie'], '1') === true || str_starts_with('5-541', $opsCodes) === true) {
            $data[$i]['laparotomie'] = '1';
        }


        $data[$i]['peritonealzytologie'] = null;

        if (str_contains($dataset['peritonealzytologie'], '1') === true || str_contains($data[$i]['opscodes'], array('8-857', '1-853.0')) === true) {
            $data[$i]['peritonealzytologie'] = '1';
        }

        $data[$i]['peritonealbiopsie'] = null;
        if (str_contains($dataset['peritonealbiopsie'], '1') === true || str_contains($data[$i]['opscodes'], array('5-543', '1-559', '1-493.6', '1-494.6')) === true) {
            $data[$i]['peritonealbiopsie'] = '1';
        }

        $data[$i]['hysterektomie'] = null;
        if (str_contains($dataset['hysterektomie'], '1') === true || str_contains($data[$i]['opscodes'], array('5-683', '5-682', '5-685')) === true) {
            $data[$i]['hysterektomie'] = '1';
        }

        $data[$i]['omentektomie'] = null;
        if ($dataset['omentektomie'] === 'colic' || $dataset['omentektomie'] === 'gastric' || str_contains($data[$i]['opscodes'], '5-543.2') === true) {
            $data[$i]['omentektomie'] = '1';
        }

        $lymphonodektomie = $dataset['lymphonodektomie'];

        if ($lymphonodektomie === null) {
            $lkPelvin = $dataset['lk_pelvin'];
            $lkPara   = $dataset['lk_para'];

            if ($lkPara == '0') {
                foreach ($opsCodes as $opsCode) {
                    if (str_starts_with($opsCode, array('5-402.2', '5-402.7', '5-404.d', '5-404.e')) === true) {
                        $lkPara = '1';
                    }
                }
            }

            if ($lkPelvin == '0') {
                foreach ($opsCodes as $opsCode) {
                    if (str_starts_with($opsCode, array('5-402.5', '5-402.a', '5-404.f', '5-404.g')) === true && str_ends_with($opsCode, 'B') === true) {
                        $lkPelvin = '1';
                    }
                }
            }

            if ($lkPara != '0' && $lkPelvin != '0') {
                $lymphonodektomie = '1';
            }

            $data[$i]['lymphonodektomie'] = $lymphonodektomie;
        }

        $stagingCheck = array(
            'laparotomie', 'peritonealzytologie', 'peritonealbiopsie', 'adnexexstirpation', 'hysterektomie',
            'omentektomie', 'lymphonodektomie'
        );

        foreach ($stagingCheck as $field) {
            if ($data[$i][$field] === null) {
                $data[$i]['op_staging'] = null;
                break;
            }
        }

        $therapySystemic = $this->recordStringToArray($dataset['therapien_systemisch'], array(
            "therapie_systemisch_id",
            "vorlage_therapie_id",
            "beginn",
            "vorlage_therapie_art",
            "therapielinie",
            "studie",
            "intention"
        ));

        unset($data[$i]['therapien_systemisch']);

        $systemicIds = array();

        foreach ($therapySystemic as $therapy) {
            $systemicIds[] = $therapy['therapie_systemisch_id'];
        }

        $diseaseId = $dataset['erkrankung_id'];

        if ($dataset['anlass'] !== 'p' && isset($tumorBehaviors[$diseaseId]) === true) {
            foreach ($tumorBehaviors[$diseaseId] as $behavior) {

                // zeitraum muss vor aktuellem sicherungsdatum liegen und nicht im aktuellen anlass
                if ($behavior['beginn'] < $dataset['datum_sicherung'] && in_array($behavior['therapie_systemisch_id'], $systemicIds) === false) {

                    // vorlage muss eine platintherapie sein
                    if (in_array($behavior['vorlage_therapie_id'], $platinIds) === true) {
                        $data[$i]['tumorverhalten']         = $behavior['tumorverhalten_platin'];
                        $data[$i]['encoded_tumorverhalten'] = $codings[$behavior['tumorverhalten_platin']];
                        $data[$i]['enddatum']               = $behavior['ende'];
                    }
                }
            }
        }

        foreach ($therapySystemic as $systemic) {
            if ($systemic['vorlage_therapie_art'] === 'cst') {
                $data[$i]['durchgef_radio_chemo'] = 1;

                if (in_array($systemic['vorlage_therapie_id'], $cisplatinIds) === true) {
                    $data[$i]['durchgef_radio_chemo_cis'] = 1;
                }
            }

            if (str_starts_with($systemic['vorlage_therapie_art'], 'c') === true) {
                $vtId = $systemic['vorlage_therapie_id'];

                if (in_array($vtId, $platinIds) === true) {
                    $data[$i]['platinhaltige_chemotherapie'] = 1;
                }

                if ($dataset['datumprimaer_rezidiv_op'] !== NULL && $systemic['beginn'] > $dataset['datumprimaer_rezidiv_op']) {
                    $data[$i]['postop_chemotherapie'] = 1;
                }

                if (strlen($firstLine) > 0 && str_contains($vtId, explode(',', $firstLine)) === true && $systemic['therapielinie'] === 'first') {
                    $data[$i]['first_line_therapie'] = 1;
                }

                if (count($monoIds) > 0 && str_contains($vtId, $monoIds) === true) {
                    $data[$i]['monotherapie'] = 1;
                }

                if (array_key_exists($vtId, $combitherapy) === true) {
                    $vtCombi = $combitherapy[$vtId];

                    if (in_array('carboplatin', $vtCombi) === true) {
                        $combitherapyDone = false;

                        if (in_array('paclitaxel', $vtCombi) === true || in_array('doxorubicinpeg', $vtCombi) === true) {
                            $combitherapyDone = true;
                        } else if (in_array('gemcitabin', $vtCombi) === true && in_array('bevacizumab', $vtCombi) === false) {
                            $combitherapyDone = true;
                        } else if (in_array('gemcitabin', $vtCombi) === true && in_array('bevacizumab', $vtCombi) === true &&
                            $dataset['anlass'] === 'r01' && in_array($diseaseId, $primaryBevacizumabId) === false
                        ) {
                            $combitherapyDone = true;
                        }

                        if ($combitherapyDone === true) {
                            $data[$i]['combitherapie'] = 1;
                        }
                    }
                }
            }
        }

        $artStaging = '';

        if (strlen($dataset['art_staging']) > 0) {
            $stagingRecords = explode('|', $dataset['art_staging']);

            $newestStagingRecord = reset($stagingRecords);
            $newestStagingRecord = explode(',', $newestStagingRecord);

            if (array_sum($newestStagingRecord) === 0) {
                $artStaging = 1;
            }
        }

        $data[$i]['art_staging'] = $artStaging;

        // Konisation
        if (str_contains(implode(',', $parsedOpsCodes), '5-671') === true) {
            $data[$i]['konisation'] = 1;
        }

        $primaryOpId  = $dataset['primaerop_eingriff_id'];
        $lymphonoOpId = null;

        // Lymphonodektomie erfolgt - $lymphonoOpId ist aus dem zeitlich frühsten
        if (strlen($dataset['lymphono_op']) > 0) {
            $lymphonoOpId = reset($this->RecordStringToArray($dataset['lymphono_op'], array('eingriff_id', 'datum'), 'datum', 'ASC'));
            $lymphonoOpId = $lymphonoOpId['eingriff_id'];

            $data[$i]['lymphono'] = 1;
        }

        $eHistologies = $this->recordStringToArray($dataset['e_histologien'], array(
            "histologie_id",
            "eingriff_id",
            "datum",
            "konisation_exzision",
            "konisation_x",
            "konisation_y",
            "konisation_z",
            "invasionstiefe",
            "invasionsbreite",
            "groesse_x",
            "groesse_y",
            "groesse_z",
            "lk_entf",
            "lk_bef",
            "lk_pelvin_entf",
            "lk_pelvin_bef",
            "lk_para_entf",
            "lk_para_bef",
            "lk_para_paracaval_entf",
            "lk_para_interaortocaval_entf",
            "lk_para_cranial_ami_entf",
            "lk_para_caudal_ami_entf",
            "lk_para_cranial_vr_entf",
            "lk_para_paracaval_bef",
            "lk_para_interaortocaval_bef",
            "lk_para_cranial_ami_bef",
            "lk_para_caudal_ami_bef",
            "lk_para_cranial_vr_bef",
            "groesste_ausdehnung",
            "kapseldurchbruch"
        ), 'datum', 'ASC'); // sorting must be ASC. DO NOT CHANGE!!

        $lymph = array(
            'lk_ges_entf' => array(),
            'lk_ges_bef' => array(),
            'lk_pelvin_entf' => array(),
            'lk_pelvin_bef' => array(),
            'lk_para_entf' => array(),
            'lk_para_bef' => array(),
            'groesste_ausdehnung' => array()
        );

        foreach ($eHistologies as $histology) {
            if ($histology['eingriff_id'] == $primaryOpId) {
                // ex konisation
                if (strlen($histology['konisation_exzision']) > 0) {
                    $data[$i]['ex_konisation'] = $histology['konisation_exzision'];
                }

                // ex konisation dimension
                if ($histology['eingriff_id'] == $primaryOpId &&
                    (strlen($histology['konisation_x']) > 0 ||
                     strlen($histology['konisation_y']) > 0 ||
                     strlen($histology['konisation_z']) > 0)
                ) {
                    $ex_konisation_dimension = array(
                        $histology['konisation_x'],
                        $histology['konisation_y'],
                        $histology['konisation_z']
                    );
                    $data[$i]['ex_konisation_dimension'] = implode(' x ', array_filter($ex_konisation_dimension));
                }

                // invasionstiefe
                if (strlen($histology['invasionstiefe']) > 0) {
                    $data[$i]['invasionstiefe'] = $histology['invasionstiefe'];
                }

                // invasionsbreite
                if (strlen($histology['invasionsbreite']) > 0) {
                    $data[$i]['invasionsbreite'] = $histology['invasionsbreite'];
                }

                // Gesamttumorgroesse
                if (strlen($histology['groesse_x']) > 0 ||
                    strlen($histology['groesse_y']) > 0 ||
                    strlen($histology['groesse_z']) > 0
                ) {
                    $gesamttgroesse = array(
                        $histology['groesse_x'],
                        $histology['groesse_y'],
                        $histology['groesse_z']
                    );

                    $data[$i]['gesamttgroesse'] = implode(' x ', array_filter($gesamttgroesse));
                }
            }

            // LK, Kapseldurchbruch und groesste Ausdehung
            if ($histology['eingriff_id'] == $lymphonoOpId) {
                $lymph['lk_ges_entf'][] = $histology['lk_entf'];
                $lymph['lk_ges_bef'][] = $histology['lk_bef'];
                $lymph['lk_pelvin_entf'][] = $histology['lk_pelvin_entf'];
                $lymph['lk_pelvin_bef'][] = $histology['lk_pelvin_bef'];

                if (strlen($histology['lk_para_entf']) > 0 ||
                    strlen($histology['lk_para_paracaval_entf']) > 0 ||
                    strlen($histology['lk_para_interaortocaval_entf']) > 0 ||
                    strlen($histology['lk_para_cranial_ami_entf']) > 0 ||
                    strlen($histology['lk_para_caudal_ami_entf']) > 0 ||
                    strlen($histology['lk_para_cranial_vr_entf']) > 0
                ) {
                    $lymph['lk_para_entf'][] = $histology['lk_para_entf'] + $histology['lk_para_paracaval_entf'] +
                        $histology['lk_para_interaortocaval_entf'] + $histology['lk_para_cranial_ami_entf'] +
                        $histology['lk_para_caudal_ami_entf'] + $histology['lk_para_cranial_vr_entf']
                    ;
                }

                if (strlen($histology['lk_para_bef']) > 0 ||
                    strlen($histology['lk_para_paracaval_bef']) > 0 ||
                    strlen($histology['lk_para_interaortocaval_bef']) > 0 ||
                    strlen($histology['lk_para_cranial_ami_bef']) > 0 ||
                    strlen($histology['lk_para_caudal_ami_bef']) > 0 ||
                    strlen($histology['lk_para_cranial_vr_bef']) > 0
                ) {
                    $lymph['lk_para_bef'][] = $histology['lk_para_bef'] + $histology['lk_para_paracaval_bef'] +
                        $histology['lk_para_interaortocaval_bef'] + $histology['lk_para_cranial_ami_bef'] +
                        $histology['lk_para_caudal_ami_bef'] + $histology['lk_para_cranial_vr_bef']
                    ;
                }

                $lymph['groesste_ausdehnung'][] = $histology['groesste_ausdehnung'];

                // Bei mehreren Formularen, bitte das neueste betrachten, in dem der Wert gefüllt ist
                if (strlen($histology['kapseldurchbruch']) > 0) {
                    $data[$i]['kapseldurchbruch'] = $histology['kapseldurchbruch'];
                }
            }
        }

        foreach ($lymph as $fieldName => $fieldCount) {
            $data[$i][$fieldName] = count($fieldCount) > 0 ? max($fieldCount) : null;
        }

        // vollständiger Befundbericht Lymphonodektomie
        if ($data[$i]['lymphono'] == 1 &&
            strlen($data[$i]['lk_ges_bef']) > 0 &&
            strlen($data[$i]['lk_pelvin_entf']) > 0 &&
            strlen($data[$i]['lk_pelvin_bef']) > 0 &&
            strlen($data[$i]['lk_para_entf']) > 0 &&
            strlen($data[$i]['lk_para_bef']) > 0 &&
            strlen($data[$i]['groesste_ausdehnung']) > 0 &&
            strlen($data[$i]['kapseldurchbruch']) > 0
        ) {
            $data[$i]['vollst_lympho'] = 1;
        }

        // radikale Hysterektomie
        if (str_contains(implode(',', $parsedOpsCodes), '5-685') === true) {
            $data[$i]['radikale_hysterektomie'] = 1;
        }

        // Exenteration
        if (str_contains(implode(',', $parsedOpsCodes), '5-687') === true) {
            $data[$i]['exenteration'] = 1;
        }

        // vollständiger histologischer Befundbericht
        if (strlen($dataset['ts_vollst_histo']) > 0 && strlen($dataset['sicherheitsabstand']) > 0) {
            $tsPt = $dataset['ts_vollst_histo'];
            $konisation = $data[$i]['konisation'];
            $exKonisation = $data[$i]['ex_konisation'];
            $invDepth = $data[$i]['invasionstiefe'];
            $invWidth = $data[$i]['invasionsbreite'];
            $exKonisationDimension = $data[$i]['ex_konisation_dimension'];
            $size = $data[$i]['gesamttgroesse'];

            $cond1 = $konisation === '0' || ($konisation == '1' && strlen($exKonisation) > 0 && strpos($exKonisationDimension, 'x') !== false);
            $cond2 = (strlen($invDepth) > 0 && strlen($invWidth) > 0 && in_array($tsPt, array('pT1a1', 'pT1a2')) === true) ||
                     (count(explode('x', $size)) === 3 && (str_starts_with($tsPt, array('pT1b', 'pT2', 'pT3')) === true || $tsPt === 'pT4'))
            ;

            if ($cond1 === true && $cond2 === true) {
                $data[$i]['vollst_histo'] = 1;
            }
        }
    }
?>
