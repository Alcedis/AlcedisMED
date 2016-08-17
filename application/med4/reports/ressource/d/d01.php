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

/**
 * Class reportD01
 */
class reportD01 extends reportExtensionD
{
    /**
     * getData
     *
     * @access  public
     * @return  array
     */
    public function getData()
    {
        $additional = $this->getAdditionalQueryData();
        $having     = "(LEFT(diagnose,3) IN ('C18','C19','C20') OR LEFT(diagnose,5) IN ('D01.0','D01.1','D01.2'))";

        $relevantSelectWhere = "ts.erkrankung_id = t.erkrankung_id AND ts.anlass = t.anlass";

        $stageCalc = stageCalc::create($this->getDB(), $this->_params['sub']);

        $relevantSelects = array(
            $stageCalc->select('c') . "AS 'uicc_prae'",
            $stageCalc->select() . "AS 'uicc'",
            "(
                    SELECT
                        IF(
                            MAX(ts.nur_zweitmeinung) IS NOT NULL OR
                            MAX(ts.nur_diagnosesicherung) IS NOT NULL OR
                            MAX(ts.kein_fall) IS NOT NULL,
                        1, NULL)
                    FROM
                        tumorstatus ts
                    WHERE
                        {$relevantSelectWhere}
                    ) AS 'nicht_zaehlen'",
            "(
                    SELECT
                        IF(
                            ts.diagnose_c19_zuordnung IS NULL AND
                            ts.diagnose = 'C19', 'C20', ts.diagnose_c19_zuordnung
                        )
                    FROM
                        tumorstatus ts
                    WHERE
                        {$relevantSelectWhere} AND
                        ts.diagnose IS NOT NULL
                    ORDER BY
                        ts.datum_sicherung DESC,
                        ts.sicherungsgrad ASC,
                        ts.datum_beurteilung DESC
                    LIMIT 1
                ) AS diagnose_c19_zuordnung",
            "(SELECT
                GROUP_CONCAT(
                    CONCAT_WS(
                        ';',
                        tm.lokalisation,
                        IFNULL(tm.resektabel, 'x')
                    ) SEPARATOR '|')
                FROM tumorstatus ts
                INNER JOIN tumorstatus_metastasen tm ON ts.tumorstatus_id = tm.tumorstatus_id
                WHERE
                    ts.erkrankung_id = t.erkrankung_id AND
                    ts.anlass = t.anlass
                GROUP BY
                    ts.erkrankung_id
            ) AS 'lebertumorstatus'",
            "(
                    SELECT
                        ts.hoehe
                    FROM tumorstatus ts
                    WHERE
                        {$relevantSelectWhere} AND
                        ts.hoehe IS NOT NULL
                    ORDER BY
                        ts.datum_sicherung DESC,
                        ts.sicherungsgrad ASC,
                        ts.datum_beurteilung DESC
                    LIMIT 1
                ) AS hoehe",
            "(
                    SELECT
                        ts.r_lokal
                    FROM tumorstatus ts
                    WHERE
                        {$relevantSelectWhere} AND
                        ts.r_lokal IS NOT NULL
                    ORDER BY
                        ts.datum_sicherung DESC,
                        ts.sicherungsgrad ASC,
                        ts.datum_beurteilung DESC
                    LIMIT 1
                ) AS r_lokal",
            "(
                    SELECT
                        ts.t
                    FROM
                        tumorstatus ts
                    WHERE
                        {$relevantSelectWhere} AND
                        ts.t IS NOT NULL
                    ORDER BY
                        ts.datum_sicherung DESC,
                        ts.sicherungsgrad ASC,
                        ts.datum_beurteilung DESC
                    LIMIT 1
                ) AS t",
            "(
                    SELECT
                        ts.n
                    FROM
                        tumorstatus ts
                    WHERE
                        {$relevantSelectWhere} AND
                        ts.n IS NOT NULL
                    ORDER BY
                        ts.datum_sicherung DESC,
                        ts.sicherungsgrad ASC,
                        ts.datum_beurteilung DESC
                    LIMIT 1
                ) AS n",
            "(
                    SELECT
                        ts.m
                    FROM
                        tumorstatus ts
                    WHERE
                        {$relevantSelectWhere} AND
                        ts.m IS NOT NULL
                    ORDER BY
                        ts.datum_sicherung DESC,
                        ts.sicherungsgrad ASC,
                        ts.datum_beurteilung DESC
                    LIMIT 1
                ) AS m",
            "(
                    SELECT
                        ts.lk_entf
                    FROM
                        tumorstatus ts
                    WHERE
                        {$relevantSelectWhere} AND
                        ts.lk_entf IS NOT NULL
                    ORDER BY
                        ts.datum_sicherung DESC,
                        ts.sicherungsgrad ASC,
                        ts.datum_beurteilung DESC
                    LIMIT 1
                ) AS lk_entf",
            "(
                    SELECT
                        ts.g
                    FROM
                        tumorstatus ts
                    WHERE
                        {$relevantSelectWhere} AND
                        ts.g IS NOT NULL
                    ORDER BY
                        ts.datum_sicherung DESC,
                        ts.sicherungsgrad ASC,
                        ts.datum_beurteilung DESC
                    LIMIT 1
                ) AS g",
            "(
                    SELECT
                        ts.t
                    FROM
                        tumorstatus ts
                    WHERE
                        {$relevantSelectWhere} AND
                        LEFT(ts.t, 1) = 'c'
                    ORDER BY
                        ts.datum_sicherung DESC,
                        ts.sicherungsgrad ASC,
                        ts.datum_beurteilung DESC
                    LIMIT 1
                ) AS ct",
            "(
                    SELECT
                        ts.n
                    FROM
                        tumorstatus ts
                    WHERE
                        {$relevantSelectWhere} AND
                        LEFT(ts.n, 1) = 'c'
                    ORDER BY
                        ts.datum_sicherung DESC,
                        ts.sicherungsgrad ASC,
                        ts.datum_beurteilung DESC
                    LIMIT 1
                ) AS cn",
            "(
                    SELECT
                        ts.m
                    FROM
                        tumorstatus ts
                    WHERE
                        {$relevantSelectWhere} AND
                        LEFT(ts.m, 1) = 'c'
                    ORDER BY
                        ts.datum_sicherung DESC,
                        ts.sicherungsgrad ASC,
                        ts.datum_beurteilung DESC
                    LIMIT 1
                ) AS cm",
            "(
                    SELECT
                        ts.m
                    FROM
                        tumorstatus ts
                    WHERE
                        {$relevantSelectWhere} AND
                        LEFT(ts.m, 1) = 'p'
                    ORDER BY
                        ts.datum_sicherung DESC,
                        ts.sicherungsgrad ASC,
                        ts.datum_beurteilung DESC
                    LIMIT 1
                ) AS pm",
            "(
                    SELECT
                        ts.tnm_praefix
                    FROM
                        tumorstatus ts
                    WHERE
                        {$relevantSelectWhere} AND
                        LEFT(ts.t, 1) = 'p'
                    ORDER BY
                        ts.datum_sicherung DESC,
                        ts.sicherungsgrad ASC,
                        ts.datum_beurteilung DESC
                    LIMIT 1
                ) AS pt_praefix",
            "(
                SELECT
                    MIN(ts.datum_sicherung)
                FROM
                    tumorstatus ts
                WHERE
                    ts.erkrankung_id = t.erkrankung_id AND
                    LEFT(ts.anlass, 1) = 'r'
                LIMIT 1
            ) AS 'erstes_rezidiv'",
            "(
                SELECT
                    MIN(ts.datum_sicherung)
                FROM
                    tumorstatus ts
                WHERE
                    ts.erkrankung_id = t.erkrankung_id AND
                    anlass = 'p'
                ORDER BY
                    ts.datum_sicherung DESC,
                    ts.sicherungsgrad ASC,
                    ts.datum_beurteilung DESC
                LIMIT 1
            ) AS 'erstes_primaer'"
        );

        $preQuery = $this->_getPreQuery($having, array_merge($relevantSelects, $additional['selects']));

        $praeopTP = $this->_getTpIds('praeop');
        $postopTP = $this->_getTpIds('postop');

        $koloskopies         = $this->_getKoloskopieIds();
        $koloskopiesElektiv  = $this->_getKoloskopieIds('elektiv');
        $koloskopiesComplete = $this->_getKoloskopieIds('complete');

        $koloskopieUntersuchungClause = $this->_buildKoloskopieClause('u.art');

        //Therapien die eine zyklische Medikation haben
        $vorlageChemoTherapie = dlookup(
            $this->_db,
            'vorlage_therapie_wirkstoff',
            "GROUP_CONCAT(DISTINCT vorlage_therapie_id ORDER BY vorlage_therapie_id ASC SEPARATOR ', ')",
            "art = 'zyk'"
        );

        $vorlageChemoTherapie = strlen($vorlageChemoTherapie) > 0 ? $vorlageChemoTherapie : "''";

        $earliestDiagnosticOpQuery = reportExtensionD::buildEarliestDiagnosticOpQuery($this->getDB(), $this->getFilteredDiseases());

        $secondDiseasesQuery = "
                SELECT
                    sit.*,
                    IF(sit.anlass = 'p', 1, 0) AS 'primaerfall',

                    IF (sit.anlass LIKE 'r%',
                        -- rezidiv
                        IFNULL(
                            IFNULL(
                                MIN(IF(op.art_rezidiv IS NOT NULL, op.datum, NULL)),
                                MIN(h.datum)
                            ),
                            IF(sit.start_date = '0000-00-00', sit.start_date_rezidiv, sit.start_date)
                        ),
                        -- primaerfall
                        IFNULL(
                            IFNULL(
                                IFNULL(
                                    IFNULL(
                                        MIN(IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = '1', s.form_date, NULL)),
                                        {$earliestDiagnosticOpQuery}
                                    ),
                                    MIN(IF(h.art = 'pr' AND h.unauffaellig IS NULL, h.datum, NULL))
                                ),
                                (SELECT MIN(aufnahmedatum) FROM aufenthalt auf WHERE auf.patient_id = sit.patient_id AND auf.fachabteilung IN ('1500', '1550', '0700', '0107', 'visz'))
                            ),
                            sit.erstes_primaer
                        )
                    ) AS 'bezugsdatum',

                    IF(COUNT(
                        DISTINCT IF(
                            th_sys.vorlage_therapie_art IN ('c', 'cst', 'ci') AND
                            th_sys.intention IN ('kurna', 'palna'),
                            th_sys.therapie_systemisch_id,
                            NULL
                        )
                    ), 1, NULL) AS 'neoadj_chemotherapie',

                    MIN(IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = '1', s.form_date, NULL)) AS 'datum_primaer_op_rezidiv_op'
                FROM ($preQuery) sit
                    {$this->_innerStatus()}

                    LEFT JOIN therapie_systemisch th_sys ON s.form = 'therapie_systemisch' AND th_sys.therapie_systemisch_id = s.form_id
                    LEFT JOIN histologie h                                            ON s.form = 'histologie' AND h.histologie_id  = s.form_id
                    LEFT JOIN eingriff op                                             ON s.form = 'eingriff' AND op.eingriff_id = s.form_id
                WHERE
                    {$this->_getNcState()}
                GROUP BY
                    sit.patient_id,
                    sit.erkrankung_id,
                    sit.anlass
            ";

        $secondDiseases = array();

        foreach (sql_query_array($this->_db, $secondDiseasesQuery) as $disease) {
            if ($disease['primaerfall'] == 0 || ($disease['neoadj_chemotherapie'] == 1 && strlen($disease['datum_primaer_op_rezidiv_op']) == 0)) {
                continue;
            }

            $secondDiseases[$disease['patient_id']][$disease['erkrankung_id']] = $disease;
        }

        $query = "
                SELECT
                    {$additional['fields']}
                    sit.nachname                                                           AS 'nachname',
                    sit.vorname                                                            AS 'vorname',
                    sit.geburtsdatum                                                       AS 'geburtsdatum',
                    sit.patient_nr                                                         AS 'patient_nr',
                    IF(sit.anlass = 'p', 1, 0)                                             AS 'primaerfall',
                    {$this->_getAnlassCases()}                                             AS 'anlass_case',
                    sit.datum_sicherung                                                    AS 'datum_sicherung',

                    IF (sit.anlass LIKE 'r%',
                        -- rezidiv
                        IFNULL(
                            IFNULL(
                                MIN(IF(op.art_rezidiv IS NOT NULL, op.datum, NULL)),
                                MIN(h.datum)
                            ),
                            IF(sit.start_date = '0000-00-00', sit.start_date_rezidiv, sit.start_date)
                        ),
                        -- primaerfall
                        IFNULL(
                            IFNULL(
                                IFNULL(
                                    IFNULL(
                                        MIN(IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = '1', s.form_date, NULL)),
                                        {$earliestDiagnosticOpQuery}
                                    ),
                                    MIN(IF(h.art = 'pr' AND h.unauffaellig IS NULL, h.datum, NULL))
                                ),
                                (SELECT MIN(aufnahmedatum) FROM aufenthalt auf WHERE auf.patient_id = sit.patient_id AND auf.fachabteilung IN ('1500', '1550', '0700', '0107', 'visz'))
                            ),
                            sit.erstes_primaer
                        )
                    )                                                                      AS 'bezugsdatum',

                    sit.diagnose                                                           AS 'diagnose',
                    sit.diagnose_c19_zuordnung                                             AS 'zugeordnet_zu',
                    sit.hoehe                                                              AS 'hoehe_ab_ano',

                    GROUP_CONCAT(DISTINCT IF(a.anamnese_id IS NOT NULL,
                        CONCAT_WS(',', a.anamnese_id, IFNULL(a.familien_karzinom, '')),
                        NULL)
                    SEPARATOR '|')                                                         AS 'positive_familienanamnese',

                    MAX(DISTINCT
                        IF(b.fam_risikosprechstunde IS NOT NULL, b.fam_risikosprechstunde, NULL)
                    )                                                                      AS 'genetische_beratung',

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
                    )                                                                      AS 'psychoonk_betreuung',

                    MAX(DISTINCT b.sozialdienst)                                           AS 'beratung_sozialdienst',
                    MAX(DISTINCT b.ernaehrungsberatung)                                    AS 'ernaehrungsberatung',

                    IF(
                        COUNT(
                            DISTINCT IF(
                                s.form = 'therapieplan' AND s.form_id IN ({$praeopTP}),
                                s.form_id,
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
                    )                                                                      AS 'tumorkonf_praeop',

                    MIN(
                        IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = '1', s.form_date, NULL)
                    )                                                                      AS 'datum_primaer_op_rezidiv_op',

                    IF(
                        MIN(IF(
                            1 IN (op.art_primaertumor, op.art_rezidiv),
                            op.eingriff_id, NULL)
                        ) IS NOT NULL,
                        IF(
                            MIN(
                                IF(
                                    1 IN (op.art_primaertumor, op.art_rezidiv),
                                    op.notfall,
                                    NULL
                                )
                            ) IS NULL,
                            1,
                            0
                        ),
                        NULL
                    )                                                                      AS 'elek_primaer_oprezidiv_op',

                    MAX(
                        IF(
                            1 IN (op.art_primaertumor, op.art_rezidiv),
                            (
                                SELECT
                                    CONCAT_WS(', ', u.nachname, u.vorname)
                                FROM user u
                                WHERE u.user_id = op.operateur1_id LIMIT 1
                            ),
                            NULL
                        )
                    )                                                                      AS 'operateur',

                MAX(IF(
                    1 IN (op.art_primaertumor, op.art_rezidiv),
                    (SELECT CONCAT_WS(', ', u.nachname, u.vorname) FROM user u WHERE u.user_id = op.operateur2_id LIMIT 1),
                    NULL
                ))                                                                       AS 'operateur_zweit',

                GROUP_CONCAT(DISTINCT IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = '1',
                    s.report_param,
                    NULL
                ) SEPARATOR '+#+^!')                                                         AS 'anastomose_durchgefuehrt',

                IF(
                    COUNT(
                        DISTINCT IF(
                            s.form = 'eingriff' AND LEFT(s.report_param, 1) = '1' AND
                            (
                                LOCATE('5-400',   SUBSTRING(s.report_param, 5)) != 0 OR
                                LOCATE('5-401',   SUBSTRING(s.report_param, 5)) != 0 OR
                                LOCATE('5-402',   SUBSTRING(s.report_param, 5)) != 0 OR
                                LOCATE('5-406',   SUBSTRING(s.report_param, 5)) != 0 OR
                                LOCATE('5-407.2', SUBSTRING(s.report_param, 5)) != 0 OR
                                LOCATE('5-407.3', SUBSTRING(s.report_param, 5)) != 0 OR
                                LOCATE('5-407.4', SUBSTRING(s.report_param, 5)) != 0 OR
                                LOCATE('5-407.x', SUBSTRING(s.report_param, 5)) != 0 OR
                                LOCATE('5-407.y', SUBSTRING(s.report_param, 5)) != 0 OR
                                LOCATE('5-408.5', SUBSTRING(s.report_param, 5)) != 0 OR
                                LOCATE('5-455.4', SUBSTRING(s.report_param, 5)) != 0 OR
                                LOCATE('5-455.6', SUBSTRING(s.report_param, 5)) != 0 OR
                                LOCATE('5-458.0', SUBSTRING(s.report_param, 5)) != 0 OR
                                LOCATE('5-458.1', SUBSTRING(s.report_param, 5)) != 0 OR
                                LOCATE('5-455.5', SUBSTRING(s.report_param, 5)) != 0
                            ),
                            s.form_id,
                            NULL
                        )
                    ),
                    1,
                    NULL
                )                                                                        AS 'lymphadenektomie_durchgefuehrt',

                MAX(op.schnellschnitt)                                                   AS 'schnellschnitt',

                MIN(op.schnellschnitt_dauer)                                             AS 'dauer_versand_durchsage',

                IF(
                    COUNT(
                        DISTINCT IF(
                            s.form = 'eingriff' AND LEFT(s.report_param, 1) = '1' AND
                            (
                                LOCATE('5-455',   SUBSTRING(s.report_param, 5)) != 0 OR
                                LOCATE('5-456',   SUBSTRING(s.report_param, 5)) != 0 OR
                                LOCATE('5-458',   SUBSTRING(s.report_param, 5)) != 0 OR
                                LOCATE('5-470',   SUBSTRING(s.report_param, 5)) != 0 OR
                                LOCATE('5-452.0', SUBSTRING(s.report_param, 5)) != 0 OR
                                LOCATE('5-452.1', SUBSTRING(s.report_param, 5)) != 0
                            ),
                            s.form_id,
                            NULL
                        )
                    ),
                    1,
                    NULL
                )                                                                        AS 'operativer_fall_kolon',

                IF(
                    COUNT(
                        DISTINCT IF(
                            s.form = 'eingriff' AND LEFT(s.report_param, 1) = '1' AND
                            (
                                LOCATE('5-484',    SUBSTRING(s.report_param, 5)) != 0 OR
                                LOCATE('5-485',    SUBSTRING(s.report_param, 5)) != 0 OR
                                LOCATE('5-456.1',  SUBSTRING(s.report_param, 5)) != 0
                             ),
                             s.form_id,
                             NULL
                        )
                    ),
                    1,
                    NULL
                )                                                                        AS 'operativer_fall_rektum',

                IF(
                    MAX(op.tme) = 1,
                    1,
                    IF(
                        COUNT(DISTINCT op.eingriff_id) > 0 AND MAX(op.tme) IS NULL,
                        0,
                        NULL
                    )
                )                                                                        AS 'tme',

                IF(
                    MAX(op.pme) = 1,
                    1,
                    IF(
                        COUNT(DISTINCT op.eingriff_id) > 0 AND MAX(op.pme) IS NULL,
                        0,
                        NULL
                    )
                )                                                                        AS 'pme',

                GROUP_CONCAT(
                    IF(h.eingriff_id IS NOT NULL,
                        CONCAT_WS('-', h.eingriff_id,
                            CONCAT_WS(';',
                                IF(h.resektionsrand_oral IS NOT NULL,1,0),
                                IF(h.resektionsrand_aboral IS NOT NULL,1,0),
                                IF(h.resektionsrand_lateral IS NOT NULL,1,0)
                            )
                        ),
                        NULL
                    )
                SEPARATOR '|')                                                                       AS 'resektionsrand_dok',

                MIN(IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = '1', s.form_id, NULL))      AS 'primaerop_id',

                CONCAT_WS(',',
                    GROUP_CONCAT(DISTINCT IF(({$koloskopieUntersuchungClause}), u.datum, NULL) SEPARATOR ','),
                    GROUP_CONCAT(DISTINCT
                        IF(
                            s.form = 'eingriff' AND s.form_id IN ({$koloskopies}),
                            s.form_date,
                            NULL
                        )
                    SEPARATOR ',')
                )                                                                        AS 'ther_koloskopie',

                CONCAT_WS(',',
                    GROUP_CONCAT(DISTINCT IF(({$koloskopieUntersuchungClause}) AND (u.anlass != 'tn' OR u.anlass IS NULL), u.datum, NULL) SEPARATOR ','),
                    GROUP_CONCAT(DISTINCT
                        IF(
                            s.form = 'eingriff' AND s.form_id IN ({$koloskopiesElektiv}),
                            s.form_date,
                            NULL
                        )
                    SEPARATOR ',')
                )                                                                        AS 'elek_ther_koloskopie',

                CONCAT_WS(',',
                    GROUP_CONCAT(DISTINCT IF(({$koloskopieUntersuchungClause}) AND (u.anlass != 'tn' OR u.anlass IS NULL) AND u.koloskopie_vollstaendig = 1, u.datum, NULL) SEPARATOR ','),
                    GROUP_CONCAT(DISTINCT
                        IF(
                            s.form = 'eingriff' AND s.form_id IN ({$koloskopiesComplete}),
                            s.form_date,
                            NULL
                        )
                    SEPARATOR ',')
                )                                                                          AS 'anz_vollst_th_elek_koloskopien',

                COUNT(
                    DISTINCT IF(
                        LEFT(u.art, 5) = '1-650' OR u.art = '1-652.1',
                        u.untersuchung_id,
                        NULL
                    )
                )                                                                       AS 'anz_diagn_koloskopie',

                COUNT(
                    DISTINCT IF(
                        (u.anlass != 'tn' OR u.anlass IS NULL) AND (LEFT(u.art, 5) = '1-650' OR u.art = '1-652.1'),
                        u.untersuchung_id,
                        NULL
                    )
                )                                                                       AS 'anz_elek_diagn_koloskopien',

                COUNT(
                    DISTINCT IF(
                        u.koloskopie_vollstaendig = '1' AND (u.anlass != 'tn' OR u.anlass IS NULL) AND (LEFT(u.art, 5) = '1-650' OR u.art = '1-652.1'),
                        u.untersuchung_id,
                        NULL
                    )
                )                                                                       AS 'anz_vollst_diag_elek_koloskopien',

                COUNT(
                    DISTINCT IF(
                        '1' IN (
                            op.art_primaertumor,
                            op.art_lk,
                            op.art_metastasen,
                            op.art_rezidiv,
                            op.art_nachresektion,
                            op.art_revision
                        ) AND
                        op.notfall IS NULL,
                        op.eingriff_id,
                        NULL
                    )
                )                                                                       AS 'anzahl_elek_ops',

                IF(
                    (
                        DATEDIFF(
                            MIN(IF(k.revisionsoperation = 1, k.datum, NULL)),
                            MIN(IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = 1, s.form_date, NULL))
                        ) BETWEEN 0 AND 30
                        OR
                        DATEDIFF(
                            MIN(IF(op.art_revision = 1, op.datum,NULL)),
                            MIN(IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = 1, s.form_date, NULL))
                        ) BETWEEN 0 AND 30
                    ),
                    1,

                    IF(
                        DATEDIFF(
                            MIN(IF(k.revisionsoperation = 1, k.datum, NULL)),
                            MIN(IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = 1, s.form_date, NULL))
                        ) > 30
                        OR
                        DATEDIFF(
                            MIN(
                                IF(
                                    1 IN (
                                        op.art_primaertumor, op.art_rezidiv
                                    ) AND op.art_revision = 1,
                                    op.datum,
                                    NULL
                                )
                            ),
                            MIN(
                                IF(
                                    s.form = 'eingriff' AND LEFT(s.report_param, 1) = 1,
                                    s.form_date,
                                    NULL
                                )
                            )
                        ) > 30,
                        0,
                        IF(MAX(k.revisionsoperation) = 0, 0, NULL)
                    )
                )                                                                  AS 'revisionsop_erforderlich',

                sit.lebertumorstatus                                               AS 'lebermetastase_nicht_resektabel',

                sit.lebertumorstatus                                               AS 'lebermetastase_resektabel',

                IF(
                    COUNT(DISTINCT IF(
                        s.form = 'eingriff' AND
                        (
                            LOCATE('5-501',    SUBSTRING(s.report_param, 5)) != 0 OR
                            LOCATE('5-502',    SUBSTRING(s.report_param, 5)) != 0 OR
                            LOCATE('5-509',    SUBSTRING(s.report_param, 5)) != 0
                        ),
                        s.form_id,
                        NULL
                    )), 1, NULL
                )                                                                        AS 'lebermetastasenresektion',

                IF(
                    IFNULL(
                        MIN(IF(th_sys.vorlage_therapie_id IN ({$vorlageChemoTherapie}), th_sys.beginn, NULL)),
                        '9999-12-31'
                    ) < MAX(
                        IF(
                            s.form = 'eingriff' AND (
                            LOCATE('5-501', SUBSTRING(s.report_param, 5)) != 0 OR
                            LOCATE('5-502', SUBSTRING(s.report_param, 5)) != 0 OR
                            LOCATE('5-509', SUBSTRING(s.report_param, 5)) != 0),
                            s.form_date,
                            '0000-00-00'
                        )
                    ),
                    1, NULL
                )                                                                       AS 'sekundaere_lebermetastasenresektion',

                GROUP_CONCAT(DISTINCT IF(s.form = 'eingriff',
                    s.report_param,
                    NULL
                ) SEPARATOR '+#+^!')                                                      AS 'stomaanlage',

                MAX(op.stomaposition)                                                     AS 'stomaposition_anzeichnung',

                IF(
                    COUNT(
                        DISTINCT IF(
                            u.art IN ('3-e09.y', '3-805', '3-82a', '3-206', '3-226', '3-e08.y'),
                            u.untersuchung_id,
                            NULL
                        )
                    ) > 0,
                    1,
                    NULL
                )                                                                         AS 'duennschischt_becken',

                COUNT(IF(op.mesorektale_faszie IS NOT NULL, 1, null)) > 0 OR
                COUNT(IF(u.mesorektale_faszie IS NOT NULL, 1, null)) > 0                  AS 'faszie_abstand',

                GROUP_CONCAT(DISTINCT
                    IF(k.komplikation IN ('wi','wa1','wa2','wa3','wctc2') AND k.eingriff_id IS NOT NULL,
                       CONCAT_WS('|', k.eingriff_id, k.datum),
                       NULL
                    )
                    SEPARATOR ','
                )                                                                         AS 'wund_30',

                GROUP_CONCAT(DISTINCT
                    IF(op.eingriff_id IS NOT NULL AND op.notfall IS NULL, CONCAT_WS('|', op.eingriff_id, op.datum), NULL)
                    SEPARATOR ','
                )                                                                         AS 'wund_30_elektivops',

                GROUP_CONCAT(DISTINCT k.komplikation SEPARATOR '|')                      AS 'komplikation',

                GROUP_CONCAT(DISTINCT
                    IF(
                        s.form = 'eingriff' AND (
                            SUBSTRING(s.report_param, 5) LIKE '%5-452.2%' OR
                            SUBSTRING(s.report_param, 5) LIKE '%5-452.5%' OR
                            SUBSTRING(s.report_param, 5) LIKE '%5-482._1%'
                        ),
                        s.form_id,
                        NULL
                    )
                )                                                                          AS 'alc_ther_koloskop_ops',

                GROUP_CONCAT(DISTINCT IF(k.eingriff_id IS NOT NULL, k.eingriff_id, NULL))  AS 'alc_ther_koloskop_komp',

                IF(COUNT(k.komplikation_id) > 0, 0, NULL)                               AS 'infolge_ther_koloskopie',

                sit.lk_entf                                                             AS 'lk_entfernt',
                sit.morphologie                                                         AS 'icd_o_3',
                null                                                                    AS 'adenokarzinom',
                sit.ct                                                                  AS 'ct',
                sit.cn                                                                  AS 'cn',
                sit.cm                                                                  AS 'cm',
                ## Beachten: UICC wird am Ende nochmals nachverarbeitet! ##
                sit.uicc_prae                                                           AS 'uicc_prae',
                sit.pt_praefix                                                          AS 'pt_praefix',
                sit.pt                                                                  AS 'pt',
                sit.pn                                                                  AS 'pn',
                sit.pm                                                                  AS 'pm',

                ## Beachten: UICC wird am Ende nochmals nachverarbeitet! ##
                null                                                                    AS 'uicc_nach_neoadj_th',
                sit.uicc                                                                AS 'uicc',

                sit.r                                                                   AS 'r',

                sit.r_lokal                                                             AS 'r_lokal',

                IF(
                   sit.g IS NOT NULL,
                   (SELECT bez FROM l_basic WHERE klasse = 'g' AND code = sit.g)
                   ,
                   NULL
                )                                                                       AS 'g',

                MIN(h.mercury)                                                          AS 'op_qualitaet_mercury',

                MAX(h.msi)                                                              AS 'msi_untersuchung',

                IF(
                   COUNT(
                      DISTINCT IF(
                         s.form = 'therapieplan' AND s.form_id IN ({$postopTP}),
                         s.form_id,
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
                )                                                                          AS 'tumorkonf_postop',

                  GROUP_CONCAT(DISTINCT
                      IF(s.form = 'studie',
                          CONCAT_WS('|', s.form_id, DATE_FORMAT(s.report_param, '%d.%m.%Y')),
                          NULL
                      )
                      SEPARATOR ', '
                   )                                                                  AS 'datum_studie',

                   COUNT(DISTINCT IF(s.form = 'studie', s.form_id, NULL))            AS 'count_studie',


                IF(COUNT(IF(s.form = 'fragebogen' AND s.report_param IN ({$this->_getPreSelect('zufrFragebogen')}), s.form_id, NULL)),1, NULL) AS 'befragungsbogen_ausgefuellt',

                IF(
                   COUNT(
                      DISTINCT IF(
                         th_sys.intention IN ('kurna', 'palna'),
                         th_sys.therapie_systemisch_id,
                         NULL
                      )
                   )
                   OR
                   COUNT(
                      DISTINCT IF(
                         th_str.intention IN ('kurna', 'palna'),
                         th_str.strahlentherapie_id,
                         NULL
                      )
                ), 1, NULL)                                                                AS 'neoadj_therapie',

                IF(COUNT(
                   DISTINCT IF(
                      th_sys.vorlage_therapie_art IN ('c', 'cst', 'ci'),
                      th_sys.therapie_systemisch_id,
                      NULL
                   )
                ), 1, NULL)                                                                AS 'chemotherapie',

                IF(COUNT(
                   DISTINCT IF(
                      th_sys.vorlage_therapie_art IN ('c', 'cst', 'ci') AND
                      th_sys.intention IN ('kura', 'pala'),
                      th_sys.therapie_systemisch_id,
                      NULL
                   )
                ), 1, NULL)                                                                AS 'adj_chemotherapie',

                IF(COUNT(
                   DISTINCT IF(
                      th_sys.vorlage_therapie_art IN ('c', 'cst', 'ci') AND
                      th_sys.intention IN ('kurna', 'palna'),
                      th_sys.therapie_systemisch_id,
                      NULL
                   )
                ), 1, NULL)                                                                AS 'neoadj_chemotherapie',

                IF(
                   COUNT(th_str_vt_alleinige.vorlage_therapie_id) > 0
                , 1, NULL)                                                                AS 'neoadj_alleinige_radiotherapie',

                IF(
                   COUNT(DISTINCT th_str_vt_wirk_ch.vorlage_therapie_wirkstoff_id) OR
                   COUNT(DISTINCT th_sys_vt_wirk_ch.vorlage_therapie_wirkstoff_id),
                   1,
                   NULL
                )                                                                       AS 'neoadj_sim_radiochemotherapie',

                MAX(n.datum)                                                            AS 'letzte_nachsorge',

                sit.erstes_rezidiv                                                      AS 'erstes_rezidiv',


                MAX(x.todesdatum)                                                       AS 'todesdatum',

                IF(
                   MAX(x.tod_tumorassoziation) IS NOT NULL AND MAX(x.tod_tumorassoziation) IN ('tott', 'totn'),
                   1,
                   IF(
                      MAX(x.tod_tumorassoziation) IS NOT NULL AND MAX(x.tod_tumorassoziation) NOT IN ('tott', 'totn'),
                      0,
                      NULL
                   )
               )                                                                        AS 'tod_tumorbedingt',

                CONCAT_WS('|',
                   MIN(IF(s.form = 'sonstige_therapie', s.form_date, NULL)),
                   MIN(th_sys.beginn),
                   MIN(th_str.beginn),
                   MIN(IF('1' IN (op.art_primaertumor, op.art_lk,op.art_metastasen,op.art_rezidiv,op.art_nachresektion,op.art_revision), op.datum, NULL)),
                   '9999-12-31'
                )                                                                           AS 'max_uicc',

                sit.anlass,
                sit.start_date,
                sit.end_date,
                sit.erkrankung_id,
                sit.patient_id

            FROM ($preQuery) sit
                {$this->_innerStatus()}

                LEFT JOIN anamnese a                                              ON s.form = 'anamnese' AND a.anamnese_id = s.form_id
                LEFT JOIN histologie h                                            ON s.form = 'histologie' AND h.histologie_id  = s.form_id
                LEFT JOIN beratung b                                              ON s.form = 'beratung' AND b.beratung_id = s.form_id
                LEFT JOIN eingriff op                                             ON s.form = 'eingriff' AND op.eingriff_id = s.form_id
                LEFT JOIN untersuchung u                                          ON s.form = 'untersuchung' AND u.untersuchung_id = s.form_id
                LEFT JOIN komplikation k                                          ON s.form = 'komplikation' AND k.komplikation_id = s.form_id
                LEFT JOIN strahlentherapie th_str                                 ON s.form = 'strahlentherapie' AND th_str.strahlentherapie_id = s.form_id
                    LEFT JOIN vorlage_therapie th_str_vt_alleinige                ON th_str_vt_alleinige.vorlage_therapie_id = th_str.vorlage_therapie_id AND
                                                                                         th_str_vt_alleinige.art = 'st' AND
                                                                                         th_str.intention IN ('kurna', 'palna')

                    LEFT JOIN vorlage_therapie th_str_vt                              ON th_str_vt.vorlage_therapie_id = th_str.vorlage_therapie_id AND
                                                                                         th_str_vt.art = 'cst' AND
                                                                                         th_str.intention IN ('kurna', 'palna')

                       LEFT JOIN vorlage_therapie_wirkstoff th_str_vt_wirk_str           ON th_str_vt_wirk_str.vorlage_therapie_id = th_str_vt.vorlage_therapie_id AND
                                                                                            th_str_vt_wirk_str.art = 'str'


                          LEFT JOIN vorlage_therapie_wirkstoff th_str_vt_wirk_ch            ON th_str_vt_wirk_ch.vorlage_therapie_id = th_str_vt_wirk_str.vorlage_therapie_id AND
                                                                                               th_str_vt_wirk_ch.art = 'zyk' AND (
                                                                                                  (th_str_vt_wirk_ch.zyklus_beginn <= th_str_vt_wirk_str.zyklus_beginn AND
                                                                                                  th_str_vt_wirk_str.zyklus_beginn <= (th_str_vt_wirk_ch.zyklus_beginn + th_str_vt_wirk_ch.zyklus_anzahl)) OR

                                                                                                  (th_str_vt_wirk_str.zyklus_beginn <= th_str_vt_wirk_ch.zyklus_beginn AND
                                                                                                  th_str_vt_wirk_ch.zyklus_beginn <= (th_str_vt_wirk_str.zyklus_beginn + th_str_vt_wirk_str.zyklus_anzahl))
                                                                                               )

                LEFT JOIN therapie_systemisch th_sys                              ON s.form = 'therapie_systemisch' AND th_sys.therapie_systemisch_id = s.form_id

                    LEFT JOIN vorlage_therapie th_sys_vt                           ON th_sys_vt.vorlage_therapie_id = th_sys.vorlage_therapie_id AND
                                                                                      th_sys_vt.art = 'cst' AND
                                                                                      th_sys.intention IN ('kurna', 'palna')

                        LEFT JOIN vorlage_therapie_wirkstoff th_sys_vt_wirk_str           ON th_sys_vt_wirk_str.vorlage_therapie_id = th_sys_vt.vorlage_therapie_id AND
                                                                                            th_sys_vt_wirk_str.art = 'str'

                            LEFT JOIN vorlage_therapie_wirkstoff th_sys_vt_wirk_ch            ON th_sys_vt_wirk_ch.vorlage_therapie_id = th_sys_vt_wirk_str.vorlage_therapie_id AND
                                                                                               th_sys_vt_wirk_ch.art = 'zyk' AND (
                                                                                                  (th_sys_vt_wirk_ch.zyklus_beginn <= th_sys_vt_wirk_str.zyklus_beginn AND
                                                                                                  th_sys_vt_wirk_str.zyklus_beginn <= (th_sys_vt_wirk_ch.zyklus_beginn + th_sys_vt_wirk_ch.zyklus_anzahl)) OR

                                                                                                  (th_sys_vt_wirk_str.zyklus_beginn <= th_sys_vt_wirk_ch.zyklus_beginn AND
                                                                                                  th_sys_vt_wirk_ch.zyklus_beginn <= (th_sys_vt_wirk_str.zyklus_beginn + th_sys_vt_wirk_str.zyklus_anzahl))
                                                                                               )

                LEFT JOIN sonstige_therapie th_son                                ON s.form = 'sonstige_therapie'     AND th_son.sonstige_therapie_id     = s.form_id

                LEFT JOIN nachsorge n                                             ON s.form = 'nachsorge' AND LOCATE(CONCAT_WS('','-',sit.erkrankung_id,'-'), s.report_param) > 0 AND
                                                                                      n.nachsorge_id = s.form_id

                LEFT JOIN abschluss x                                             ON s.form = 'abschluss' AND x.abschluss_id = s.form_id

                 {$additional['joins']}
              WHERE
                 {$this->_getNcState()}
              GROUP BY
                 sit.patient_id,
                 sit.erkrankung_id,
                 sit.anlass
              HAVING
                 {$this->_buildHaving()}
                 {$additional['condition']}
              ORDER BY
                 nachname, vorname, bezugsdatum
            ";

        $data = sql_query_array($this->_db, $query);

        $lKomplikation = getLookup($this->_db, 'komplikation');

        foreach ($data as $i => $record) {
            $erkrankungId = $record['erkrankung_id'];
            $patientId    = $record['patient_id'];
            $primaerfall  = $record['primaerfall'];

            // check
            if ($primaerfall == 1 && $record['neoadj_chemotherapie'] == 1 && strlen($record['datum_primaer_op_rezidiv_op']) == 0) {
                $data[$i]['primaerfall'] = $primaerfall = 3;
            }

            if ($primaerfall == 1) {
                //Zweiterkrankung
                if (isset($secondDiseases[$patientId]) === true) {
                    $diagnose    = $this->_detectDiagnose($record['diagnose'], $record['zugeordnet_zu']);
                    $bezugsdatum = $record['bezugsdatum'];

                    if (strlen($bezugsdatum) > 0 && strlen($diagnose) > 0) {
                        foreach ($secondDiseases[$patientId] as $patientDisease) {
                            if ($patientDisease['erkrankung_id'] != $erkrankungId) {
                                $oDiagnose    = $this->_detectDiagnose($patientDisease['diagnose'], $patientDisease['diagnose_c19_zuordnung']);
                                $oBezugsdatum = $patientDisease['bezugsdatum'];
                                $otherSection = $this->_detectSection($diagnose, $oDiagnose);

                                if ($otherSection === true || strlen($oBezugsdatum) == 0) {
                                    continue;
                                }

                                if ($bezugsdatum > $oBezugsdatum) {
                                    $data[$i]['primaerfall'] = $primaerfall = 2;
                                    unset($secondDiseases[$patientId][$erkrankungId]);

                                    break;
                                }
                            }
                        }
                    }
                }
            }

            $koloskopieOps  = $record['alc_ther_koloskop_ops'];
            $koloskopieKomp = $record['alc_ther_koloskop_komp'];

            $data[$i]['datum_studie'] = $this->_removeIdentifier($record['datum_studie']);

            //positive_familienanamnese
            if (strlen($record['positive_familienanamnese']) > 0) {
                $anamnesis = explode('|', $record['positive_familienanamnese']);

                $value = null;

                foreach ($anamnesis as $formValues) {
                    $values = explode(',', $formValues);

                    if (end($values) == 1) {
                        $anamneseId = reset($values);

                        $where = "anamnese_id = '{$anamneseId}' AND karzinom IN ('d', 'du', 'kore') AND verwandschaftsgrad IN ('mu', 'va', 'sch', 'br', 'ze', 'zz', 'to', 'so')";

                        if (strlen(dlookup($this->_db, 'anamnese_familie', 'anamnese_familie_id', $where))) {
                            $value = 1;
                            break;
                        }
                    }
                }

                if ($value === null) {
                    foreach ($anamnesis as $formValues) {
                        $values = explode(',', $formValues);

                        if (end($values) == '0') {
                            $value = 0;
                            break;
                        }
                    }
                }

                if ($value === null) {
                    foreach ($anamnesis as $formValues) {
                        $values = explode(',', $formValues);

                        if (end($values) == 'nb') {
                            $value = 'n.b.';
                            break;
                        }
                    }
                }

                $data[$i]['positive_familienanamnese'] = $value;
            }

            //Wundinfektion 30 Tage nach elektiv op
            if (strlen($record['wund_30']) > 0) {
                $wund30 = 0;

                if (strlen($record['wund_30_elektivops']) > 0) {
                    $eingriffe = array();

                    foreach (explode(',', $record['wund_30_elektivops']) as $tmp) {
                        $tmp                    = explode('|', $tmp);
                        $eingriffe[reset($tmp)] = end($tmp);
                    }

                    foreach (explode(',', $record['wund_30']) as $tmp) {
                        $komp = explode('|', $tmp);

                        if (array_key_exists(reset($komp), $eingriffe) === true) {
                            $dif = date_diff_days($eingriffe[reset($komp)], end($komp));
                            if ($dif >= 0 && $dif <= 30) {
                                $wund30 = 1;

                                break;
                            }
                        }
                    }
                }

                $data[$i]['wund_30'] = $wund30;
            }

            unset($data[$i]['wund_30_elektivops']);

            //Stomaanlage durchgefuehrt
            if (strlen($record['stomaanlage']) > 0) {
                $ops = explode('+#+^!', $record['stomaanlage']);

                $stomaanlage = null;

                foreach ($ops as $op) {
                    $codes = explode(' ', substr($op, 4));

                    foreach ($codes as $code) {
                        if (strlen($code) > 0) {
                            $found = false;

                            switch (true) {
                                case (str_starts_with($code, '5-455') && str_ends_with($code, array(
                                        '2',
                                        '3',
                                        '4',
                                        '6'
                                    ))):
                                case (str_starts_with($code, '5-456') && str_ends_with($code, array('0', '7'))):
                                case (str_starts_with($code, '5-458') && str_ends_with($code, array('2', '3', '4'))):
                                case (str_starts_with($code, array('5-460', '5-461', '5-462', '5-463'))):
                                case (in_array($code, array(
                                    '5-e03.y',
                                    '5-e04.y',
                                    '5-e05.y',
                                    '5-e06.y',
                                    '5-e07.y',
                                    '5-485.01'
                                ))):
                                case (str_starts_with($code, '5-484') && str_ends_with($code, array('2', '6'))):
                                    $found = true;

                                    break;
                            }

                            if ($found === true) {
                                $stomaanlage = 1;
                                break 2;
                            }
                        }
                    }
                }

                $data[$i]['stomaanlage'] = $stomaanlage;
            }
            ////////////////////////////////////

            //Anastomose durchgefuehrt
            if (strlen($record['anastomose_durchgefuehrt']) > 0) {
                $ops = explode('+#+^!', $record['anastomose_durchgefuehrt']);

                $ad = null;

                foreach ($ops as $op) {
                    $codes = explode(' ', substr($op, 4));

                    foreach ($codes as $code) {
                        if (strlen($code) > 0) {
                            $found = false;

                            switch (true) {
                                case (str_starts_with($code, '5-455') && str_ends_with($code, array('1', '4', '5'))):
                                case (str_starts_with($code, '5-456') && str_ends_with($code, array(
                                        '1',
                                        '2',
                                        '3',
                                        '4',
                                        '5',
                                        '6'
                                    ))):
                                case (str_starts_with($code, '5-458') && str_ends_with($code, array('1', '4', '5'))):
                                case (str_starts_with($code, '5-484') && str_ends_with($code, array('1', '5'))):
                                case (in_array($code, array('5-459.2', '5-459.3', '5-e00.y', '5-e01.y', '5-e02.y'))):

                                    $found = true;

                                    break;
                            }

                            if ($found === true) {
                                $ad = 1;
                                break 2;
                            }
                        }
                    }
                }

                $data[$i]['anastomose_durchgefuehrt'] = $ad;
            }

            //UICC Berechnung
            $data[$i]['uicc_prae']           = $stageCalc->calcToMaxDate($record['uicc_prae'], min(explode('|', $record['max_uicc'])));
            $data[$i]['uicc']                = $stageCalc->calc($record['uicc']);
            $data[$i]['uicc_nach_neoadj_th'] = $stageCalc->getCacheValue('tnm_praefix');

            //Komplikation
            $komplikationen = strlen($record['komplikation']) > 0 ? explode('|', $record['komplikation']) : null;

            if ($komplikationen !== null) {
                $tmp = array();

                foreach ($komplikationen as $komplikation) {
                    if (isset($lKomplikation[$komplikation]) === true) {
                        $tmp[] = $lKomplikation[$komplikation];
                    }
                }

                asort($tmp);

                $data[$i]['komplikation'] = implode(',', $tmp);
            }

            unset($data[$i]['alc_ther_koloskop_ops']);
            unset($data[$i]['alc_ther_koloskop_komp']);

            if (strlen($koloskopieOps) > 0 && strlen($koloskopieKomp) > 0) {
                $koloskopieOps  = explode(',', $koloskopieOps);
                $koloskopieKomp = explode(',', $koloskopieKomp);

                foreach ($koloskopieKomp as $komp) {
                    if (in_array($komp, $koloskopieOps) === true) {
                        $data[$i]['infolge_ther_koloskopie'] = 1;
                        break;
                    }
                }
            }

            //Therapeutische Koloskopien
            $data[$i]['ther_koloskopie']                = strlen($record['ther_koloskopie']) > 0 ? count(array_unique(explode(',', $record['ther_koloskopie']))) : 0;
            $data[$i]['elek_ther_koloskopie']           = strlen($record['elek_ther_koloskopie']) > 0 ? count(array_unique(explode(',', $record['elek_ther_koloskopie']))) : 0;
            $data[$i]['anz_vollst_th_elek_koloskopien'] = strlen($record['anz_vollst_th_elek_koloskopien']) > 0 ? count(array_unique(explode(',', $record['anz_vollst_th_elek_koloskopien']))) : 0;

            //Dokumentierten Resektionsrand nur bei primaerfall = 1 anzeigen
            if ($primaerfall == 1 && strlen($record['primaerop_id']) > 0 && strlen($record['resektionsrand_dok']) > 0) {
                $primaeropId = $record['primaerop_id'];

                foreach (explode('|', $record['resektionsrand_dok']) as $resektionsrand) {
                    $r = explode('-', $resektionsrand);

                    if (reset($r) == $primaeropId) {
                        $data[$i]['resektionsrand_dok'] = min(explode(';', end($r)));
                    }
                }
            } else {
                $data[$i]['resektionsrand_dok'] = null;
            }

            //Lebermetastase nicht resektabel
            if (strlen($record['lebermetastase_nicht_resektabel']) > 0) {
                $val = null;

                foreach (explode('|', $record['lebermetastase_nicht_resektabel']) as $metastase) {
                    if (str_starts_with($metastase, 'C22.0') === false) {
                        $val = null;
                        break;
                    } elseif (str_ends_with($metastase, '0') === true) {
                        $val = 1;
                    }
                }

                $data[$i]['lebermetastase_nicht_resektabel'] = $val;
            }

            //Lebermetastase resektabel
            if (strlen($record['lebermetastase_resektabel']) > 0) {
                $val = null;

                foreach (explode('|', $record['lebermetastase_resektabel']) as $metastase) {
                    if (str_starts_with($metastase, 'C22.0') === false) {
                        $val = null;
                        break;
                    } elseif (str_starts_with($metastase, 'C22.0') === true && str_ends_with($metastase, 1) === true) {
                        $val = 1;
                    }
                }

                $data[$i]['lebermetastase_resektabel'] = $val;
            }

            // adenokarzinom
            $adenoCodes = array(
                '8140/3',
                '8213/3',
                '8211/3',
                '8201/3',
                '8220/3',
                '8230/3',
                '8244/3',
                '8261/3',
                '8263/3',
                '8480/3',
                '8210/3',
                '8221/3',
                '8262/3',
                '8265/3',
                '8481/3',
                '8490/3',
                '8510/3',
                '8560/3',
                '8574/3'
            );

            $data[$i]['adenokarzinom'] = 0;

            if (in_array($record['icd_o_3'], $adenoCodes) === true) {
                $data[$i]['adenokarzinom'] = 1;
            }
        }

        return $data;
    }


    /**
     * detectDiagnose
     *
     * entweder ist die 1. Erkrankung C20 und die 2. Erkrankung C18*
     * oder beide Erkrankungen haben eine C18* Diagnose, die aber unterschiedlich ist.
     * Als unterschiedliche Diagnosen zählen C18.1 - C18.7. C18.8 und C18.9 werden nicht als unterschiedlich gezählt,
     * da die Diagnose nicht eindeutig einem Abschnitt zugeordnet werden kann. Hier gilt folgendes:
     * C18* + C18.8 oder C18.9: frühere Erkrankung = 1, spätere = 2
     * C20 + C 18.8 oder C18.9: beide Erkrankungen werden gezählt
     *
     * @param   string $diagnose
     * @param   string $zuordnung
     * @return  mixed
     */
    protected function _detectDiagnose($diagnose, $zuordnung)
    {
        $diagnoseZuordnung = str_replace('C18', 'C18.7', $zuordnung);

        return strlen($diagnoseZuordnung) ? $diagnoseZuordnung : $diagnose;
    }


    /**
     * detectSection
     *
     * @param   string $diagnose
     * @param   string $secondDiagnose
     * @return  bool
     */
    protected function _detectSection($diagnose, $secondDiagnose)
    {
        if (str_contains($diagnose, array('C18.8', 'C18.9')) === false && str_contains($secondDiagnose, array(
                'C18.8',
                'C18.9'
            )) === false
        ) {
            if ($diagnose === $secondDiagnose) {
                return false;
            } else {
                return true;
            }
        } else {
            if (str_contains($diagnose, 'C20') === true || str_contains($secondDiagnose, 'C20') === true) {
                return true;
            } else {
                return false;
            }
        }
    }


    /**
     * _getTpIds
     *
     * @access  protected
     * @param   string  $type
     * @return  string
     */
    protected function _getTpIds($type)
    {
        $ids = $this->getCache('therapyPlans');

        if ($ids === null) {
            $this->_buildTherapyPlans();

            $ids = $this->getCache('therapyPlans');
        }

        return (count($ids[$type]) > 0 ? implode(',', $ids[$type]) : '0');
    }


    /**
     * _buildTherapyPlans
     *
     * @access  protected
     * @return  reportExtensionD
     */
    protected function _buildTherapyPlans()
    {

        $query = "
            SELECT
                s.form_id,
                IF(tp.grundlage = 'tk' AND tp.zeitpunkt = 'prae', 1, 0) AS 'praeop',
                IF(tp.grundlage = 'tk' AND tp.zeitpunkt = 'post', 1, 0) AS 'postop'
            FROM `status` s
                INNER JOIN therapieplan tp ON s.form_id = tp.therapieplan_id
            WHERE
                s.form = 'therapieplan' AND
                s.erkrankung_id IN ({$this->getFilteredDiseases()})
        ";

        $result = sql_query_array($this->_db, $query);

        $cache = array(
            'praeop' => array(),
            'postop' => array()
        );

        foreach ($result as $dataset) {
            if ($dataset['praeop'] == '1') {
                $cache['praeop'][] = $dataset['form_id'];
            }

            if ($dataset['postop'] == '1') {
                $cache['postop'][] = $dataset['form_id'];
            }
        }

        $this->setCache('therapyPlans', $cache);

        return $this;
    }
}


?>
