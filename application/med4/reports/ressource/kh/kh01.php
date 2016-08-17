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

    /* @var customReport $this */

    $separator_row = "\x01";
    $separator_col = "\x02";

    $having = "(LEFT(diagnose,3) IN ('C00','C01','C02','C03','C04','C05','C06','C09','C10','C11','C12','C13','C14','C32') OR
        diagnose = 'D00.0' OR diagnose = 'D02.0')
    ";

    $relevantSelectWhere = "ts.erkrankung_id = t.erkrankung_id AND ts.anlass = t.anlass";
    $relevantSelectOrder = "ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1";
    $rezidivOneStepCheck = $this->_rezidivOneStepCheck();

    $stageCalc = stageCalc::create($this->_db, $this->_params['sub']);

    $localeInfiltration = "(ts.t IN ('pTX', 'pT0', 'pT1', 'pT2', 'pT3') OR (LOCATE('pT4', ts.t) != 0 AND ts.infiltration IS NOT NULL))";
    $infilttrationDetected = "((
            RIGHT(ts.n, 2) IN ('NX', 'N0') OR
            ((RIGHT(ts.n, 2) IN ('N1', 'N3') OR LOCATE('N2', ts.n) != 0) AND ts.befallen_n IS NOT NULL)
        ) AND (
            RIGHT(ts.m, 2) = 'M0' OR (RIGHT(ts.m, 2) = 'M1' AND ts.befallen_m IS NOT NULL)
        )
    )";

    $relevantSelects = array(
        $stageCalc->select() . "AS 'uicc'",
        "(
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
        ) AS 'nicht_zaehlen'",
        "(SELECT ts.t FROM tumorstatus ts WHERE {$relevantSelectWhere} AND LEFT(ts.t, 1) = 'c' {$relevantSelectOrder})                    AS ct",
        "(SELECT ts.n FROM tumorstatus ts WHERE {$relevantSelectWhere} AND LEFT(ts.n, 1) = 'c' {$relevantSelectOrder})                    AS cn",
        "(SELECT ts.m FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.m IS NOT NULL {$relevantSelectOrder})                       AS m",
        "(SELECT ts.lokalisation FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.lokalisation IS NOT NULL {$relevantSelectOrder}) AS lokalisation",
        "(SELECT CONCAT_WS(' x ', ts.groesse_x, ts.groesse_y, ts.groesse_z) FROM tumorstatus ts
        WHERE {$relevantSelectWhere} AND (ts.groesse_x IS NOT NULL OR ts.groesse_y IS NOT NULL OR ts.groesse_z IS NOT NULL) {$relevantSelectOrder}) AS tumorgroesse",
        "(SELECT ts.g FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.g IS NOT NULL {$relevantSelectOrder})       AS g",
        "(SELECT ts.invasionstiefe FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.invasionstiefe IS NOT NULL {$relevantSelectOrder})       AS invasionstiefe",
        "(SELECT ts.l FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.l IS NOT NULL {$relevantSelectOrder})       AS l",
        "(SELECT ts.v FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.v IS NOT NULL {$relevantSelectOrder})       AS v",
        "(SELECT ts.ppn FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.ppn IS NOT NULL {$relevantSelectOrder})   AS ppn",
        "(SELECT 1 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND {$localeInfiltration} {$relevantSelectOrder}) AS lokale_infiltration",
        "(SELECT 1 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND {$infilttrationDetected} {$relevantSelectOrder}) AS infiltration_bestimmt",
        "(SELECT ts.r_lokal FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.r_lokal IS NOT NULL {$relevantSelectOrder}) AS r_lokal",
        "(SELECT 1 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND (
            ts.lokalisation IS NOT NULL AND
            (ts.groesse_x IS NOT NULL OR ts.groesse_y IS NOT NULL OR ts.groesse_z IS NOT NULL) AND
            ts.morphologie IS NOT NULL AND
            ts.g IS NOT NULL AND
            ts.invasionstiefe IS NOT NULL AND
            ts.l IS NOT NULL AND
            ts.v IS NOT NULL AND
            ts.ppn IS NOT NULL AND
            {$localeInfiltration} AND
            LEFT(ts.t, 1) = 'p' AND
            {$infilttrationDetected} AND
            ts.r_lokal IS NOT NULL
        ) {$relevantSelectOrder}) AS histologisch_vollst",

        "(SELECT ts.resektionsrand FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.resektionsrand IS NOT NULL {$relevantSelectOrder}) AS resektionsrand",

        "(SELECT ts.datum_sicherung FROM tumorstatus ts WHERE {$this->_rezidivOneStepCheck()} ORDER BY ts.datum_sicherung ASC LIMIT 1) AS rezidiv_datum",
        "(SELECT ts.datum_sicherung FROM tumorstatus ts WHERE {$rezidivOneStepCheck} AND ts.rezidiv_lokal IS NOT NULL       ORDER BY ts.datum_sicherung ASC LIMIT 1) AS rezidiv_lokal_datum",
        "(SELECT ts.datum_sicherung FROM tumorstatus ts WHERE {$rezidivOneStepCheck} AND ts.rezidiv_lk IS NOT NULL          ORDER BY ts.datum_sicherung ASC LIMIT 1) AS rezidiv_lk_datum",
        "(SELECT ts.datum_sicherung FROM tumorstatus ts WHERE {$rezidivOneStepCheck} AND ts.rezidiv_metastasen IS NOT NULL  ORDER BY ts.datum_sicherung ASC LIMIT 1) AS rezidiv_metastasen_datum",
    );

    $preQuery = $this->_getPreQuery($having, array_merge($relevantSelects, $additionalTsSelects));

    $primaryCases = $this->_detectPrimaryCases($preQuery);

    $query = "
        SELECT
            {$additionalFields}
            sit.nachname               AS 'nachname',
            sit.vorname                AS 'vorname',
            sit.geburtsdatum           AS 'geburtsdatum',
            sit.patient_nr             AS 'patient_nr',
            null AS 'primaerfall',
            {$this->_getAnlassCases()} AS 'anlass_case',
            IF(
                sit.anlass LIKE 'r%' AND MIN(h.datum) IS NULL,
                IF(sit.start_date = '0000-00-00', sit.start_date_rezidiv, sit.start_date),
                MIN(h.datum)
            )                          AS 'bezugsdatum',
            sit.diagnose               AS 'diagnose',
            sit.morphologie            AS 'morphologie',

            MAX(
                IF(
                    1 IN (op.art_primaertumor, op.art_rezidiv),
                    op.datum,
                    NULL
                )
            )                          AS 'datumprimaer_op',
            sit.ct                     AS 'ct',
            sit.cn                     AS 'cn',
            sit.pt                     AS 'pt',
            sit.pn                     AS 'pn',
            sit.m                      AS 'm',
            ## Beachten: UICC wird am Ende nochmals nachverarbeitet! ##
            sit.uicc                   AS 'uicc',
            IF(
                COUNT(DISTINCT IF(
                    tp.grundlage = 'tk' AND
                    tp.zeitpunkt = 'prae',
                    tp.therapieplan_id,
                    NULL
                ))
                OR
                COUNT(DISTINCT IF(
                    s.form = 'konferenz_patient' AND
                    LEFT(s.report_param, 4) = 'prae' AND
                    SUBSTRING(s.report_param, 6) != '' AND
                    SUBSTRING(s.report_param, 6) BETWEEN sit.start_date AND sit.end_date,
                    s.form_id,
                    NULL
                )),
                1, NULL
            )                          AS 'praeop_tumorkonf',

            IF(
                COUNT(DISTINCT IF(
                    tp.grundlage = 'tk' AND
                    tp.zeitpunkt = 'post',
                    tp.therapieplan_id,
                    NULL
                ))
                OR
                COUNT(DISTINCT IF(
                    s.form = 'konferenz_patient' AND
                    LEFT(s.report_param, 4) = 'post' AND
                    SUBSTRING(s.report_param, 6) != '' AND
                    SUBSTRING(s.report_param, 6) BETWEEN sit.start_date AND sit.end_date,
                    s.form_id,
                    NULL
                )),
                1, NULL
            )                          AS 'postop_tumorkonf',

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
            )                         AS 'psychoonk_betreuung',

            MAX(DISTINCT
              b.sozialdienst
            )                         AS 'betreuungsozialdienst',

            GROUP_CONCAT(DISTINCT
                IF(s.form = 'studie',
                    CONCAT_WS('|', s.form_id, DATE_FORMAT(s.report_param, '%d.%m.%Y')),
                    NULL
                )
                SEPARATOR ', '
            )                         AS 'datum_studie',

            COUNT(DISTINCT IF(s.form = 'studie', s.form_id, NULL)) AS 'count_studie',

            COUNT(DISTINCT IF(op.art_revision = '1', op.eingriff_id, NULL)) > 0 AS 'revisions_op',

            GROUP_CONCAT(DISTINCT IF(op.eingriff_id IS NOT NULL, op.eingriff_id, NULL)) AS 'eingriff_ids',

            GROUP_CONCAT(DISTINCT IF(k.eingriff_id IS NOT NULL AND k.revisionsoperation IS NOT NULL,
                CONCAT_WS('|', k.eingriff_id, k.revisionsoperation),
                NULL
            ))                        AS 'revisions_kompl',

            IF(COUNT(DISTINCT IF(u.hno_untersuchung = '1', 1, NULL)) > 0, 1, NULL) AS 'hno_untersuchung',

            IF(COUNT(DISTINCT IF(
                (LOCATE('3-200', u.art) != 0 OR
                 LOCATE('3-220', u.art) != 0 OR
                 LOCATE('3-800', u.art) != 0 OR
                 LOCATE('3-820', u.art) != 0
                ) AND sit.cn IS NOT NULL, 1, NULL
            )) > 0, 1, NULL)   AS 'n_bestimmung_a',

            IF(COUNT(DISTINCT IF(
                (LOCATE('3-201', u.art) != 0 OR
                 LOCATE('3-221', u.art) != 0 OR
                 LOCATE('3-801', u.art) != 0 OR
                 LOCATE('3-821', u.art) != 0
                ) AND sit.cn IS NOT NULL, 1, NULL
            )) > 0, 1, NULL)   AS 'n_bestimmung_b',
            null                    AS 'n_bestimmung',

            IF(COUNT(DISTINCT IF(
                LOCATE('3-202', u.art) != 0 OR
                LOCATE('3-222', u.art) != 0, 1, NULL
            )) > 0, 1, NULL)   AS 'thorax_ct',

            sit.lokalisation        AS 'lokalisation',
            sit.tumorgroesse        AS 'tumorgroesse',
            sit.g                   AS 'g',
            sit.invasionstiefe      AS 'invasionstiefe',
            sit.l                   AS 'l',
            sit.v                   AS 'v',
            sit.ppn                 AS 'ppn',
            sit.lokale_infiltration AS 'lokale_infiltration',

            sit.infiltration_bestimmt AS 'infiltration_bestimmt',
            sit.r_lokal             AS 'r_lokal',
            sit.histologisch_vollst AS 'histologisch_vollst',

            IF (COUNT(DISTINCT
                IF(s.form = 'eingriff' AND LOCATE('5-403', s.report_param) != 0, s.form_id, NULL)
            ) > 0, 1, NULL) AS 'neck_dissection',

            IF (COUNT(str.strahlentherapie_id) > 0, 1, NULL) AS 'strahlen_durchgef',

            IF (
                MAX(str.strahlentherapie_id) IS NOT NULL,
                GROUP_CONCAT(DISTINCT IF(str.strahlentherapie_id IS NOT NULL, CONCAT_WS(',', str.beginn, str.unterbrechung), NULL) SEPARATOR '|'),
                NULL
            ) AS 'strahlen_unterbrochen',

            sit.resektionsrand     AS 'resektionsrand',

            IF(COUNT(
                DISTINCT IF(
                   sys.vorlage_therapie_art IN ('st', 'cst'),
                   sys.therapie_systemisch_id,
                   NULL
                )) OR
                COUNT(
                    DISTINCT IF(
                       str.vorlage_therapie_art IN ('st', 'cst'),
                       str.strahlentherapie_id,
                       NULL
                    )
                )
            , 1, NULL)              AS 'str_che_durchgef',

            CONCAT_WS('|',
                GROUP_CONCAT(DISTINCT IF(
                    sys.vorlage_therapie_art IN ('st', 'cst'),
                    CONCAT_WS(',', sys.beginn, IFNULL(sys.zahnarzt, 0)),
                    NULL
                ) SEPARATOR '|'),
                GROUP_CONCAT(DISTINCT IF(
                    str.vorlage_therapie_art IN ('st', 'cst'),
                    CONCAT_WS(',', str.beginn, IFNULL(str.zahnarzt, 0)),
                    NULL
                ) SEPARATOR '|')
            ) AS 'zahnarzt',

            MAX(n.datum)                AS 'letzte_nachsorge',

            sit.rezidiv_datum           AS 'datumrezidiv',

            IF(sit.rezidiv_lokal_datum IS NOT NULL AND sit.rezidiv_lk_datum IS NOT NULL,
               IF(
                  sit.rezidiv_lokal_datum < sit.rezidiv_lk_datum,
                  sit.rezidiv_lokal_datum,
                  sit.rezidiv_lk_datum
               ),
               IFNULL(sit.rezidiv_lokal_datum, sit.rezidiv_lk_datum)
            )                            AS 'datumlokalrezidiv',

            sit.rezidiv_metastasen_datum AS 'datummetastase',

            IF(
                MAX(x.abschluss_grund) IS NOT NULL AND MAX(x.abschluss_grund) = 'lost',
                1,
                IF(
                   MAX(x.abschluss_grund) IS NOT NULL AND MAX(x.abschluss_grund) != 'lost',
                   0,
                   NULL
                )
            )                           AS 'losttofu',

            MAX(x.todesdatum)           AS 'todesdatum',

            IF(
                MAX(x.tod_tumorassoziation) IS NOT NULL AND MAX(x.tod_tumorassoziation) IN ('tott', 'totn'),
                1,
                IF(
                   MAX(x.tod_tumorassoziation) IS NOT NULL AND MAX(x.tod_tumorassoziation) NOT IN ('tott', 'totn'),
                   0,
                   NULL
                )
            )                           AS 'todtumorbedingt',

            sit.erkrankung_id,
            sit.anlass,
            sit.patient_id
        FROM ($preQuery) sit
            {$this->_innerStatus()}

            {$this->_statusJoin('histologie h')}
            {$this->_statusJoin('eingriff op')}
            {$this->_statusJoin('therapieplan tp')}
            {$this->_statusJoin('beratung b')}
            {$this->_statusJoin('komplikation k')}
            {$this->_statusJoin('untersuchung u')}
            {$this->_statusJoin('strahlentherapie str')}
            {$this->_statusJoin('therapie_systemisch sys')}

            LEFT JOIN abschluss x ON s.form = 'abschluss' AND
                                     x.abschluss_id = s.form_id

            LEFT JOIN nachsorge n ON s.form = 'nachsorge' AND
                                     LOCATE(CONCAT_WS('','-',sit.erkrankung_id,'-'), s.report_param) > 0 AND
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

    foreach ($data as $i => $record) {
        $diseaseId = (int) $record['erkrankung_id'];
        $case = $record['anlass'];

        // Ticket #22806
        $data[$i]['n_bestimmung'] = '';
        if (1 == $record['n_bestimmung_a'] && 1 == $record['n_bestimmung_b']) {
            $data[$i]['n_bestimmung'] = '1';
        }
        unset($data[$i]['n_bestimmung_a']);
        unset($data[$i]['n_bestimmung_b']);

        $primaryCase = array_key_exists($diseaseId . $case, $primaryCases) === true ? $primaryCases[$diseaseId . $case] : null;

        if ($primaryCase === null && str_starts_with($case, 'r') === true) {
            $primaryCase = 0;
        }

        $data[$i]['primaerfall']  = $primaryCase;
        $data[$i]['uicc']         = $stageCalc->calc($record['uicc']);
        $data[$i]['datum_studie'] = $this->_removeIdentifier($record['datum_studie']);

        if ($record['revisions_op'] != 1) {
            $complications = array();
            $ops = array();
            $state = NULL;

            if (strlen($record['revisions_kompl']) > 0) {
                foreach (explode(',', $record['revisions_kompl']) as $tmpData) {
                    $tmpExplode = explode('|', $tmpData);
                    $complications[] = array('eingriff_id' => $tmpExplode[0], 'revisionsoperation' => $tmpExplode[1]);
                }
            }

            if (strlen($record['eingriff_ids']) > 0) {
                $ops = explode(',', $record['eingriff_ids']);
            }

            foreach ($complications as $complication) {
                if (in_array($complication['eingriff_id'], $ops) === true) {
                    $state = $complication['revisionsoperation'];

                    if ($state == 1) {
                        break;
                    }
                }
            }

            $data[$i]['revisions_op'] = $state;
        }

        unset($data[$i]['revisions_kompl']);
        unset($data[$i]['eingriff_ids']);

        $data[$i]['strahlen_unterbrochen'] = $this->_selectMinByDate($record['strahlen_unterbrochen']);

        $zahnarzt = $this->_selectMinByDate($record['zahnarzt']);

        if (strlen($zahnarzt) > 0) {
            if ($zahnarzt != '1') {
                $zahnarzt = null;
            }
        }

        $data[$i]['zahnarzt'] = $zahnarzt;
    }
?>
