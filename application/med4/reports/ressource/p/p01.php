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

class reportP01 extends reportExtensionP
{
    /**
     * referenceDateFields
     * (attention: order of this fields are very important because they reflect the priority order of reference date)
     *
     * @access  public
     * @var     array
     */
    public static $referenceDateFields = array(
        'bezug_rpe',
        'bezug_rze',
        'bezug_perk',
        'bezug_seed',
        'bezug_hdr',
        'bezug_as',
        'bezug_ww',
        'bezugsdatum_cpz_andere_lokale_therapie',
        'bezugsdatum_cpz_ausschliesslich_systemisch',
        'bezugsdatum_cpz_andere_behandlung'
    );


    /**
     * _swageSections
     *
     * @access  protected
     * @var     array
     */
    protected $_swageSections = array(
        'sbr', 'sbl', 'blr', 'bll', 'br', 'bl', 'tr', 'tl', 'mlr', 'mll', 'mr', 'ml', 'alr', 'all', 'ar', 'al'
    );


    /**
     * getData
     *
     * @access  public
     * @return  array
     */
    public function getData()
    {
        $config       = $this->loadConfigs('p01', false, true);

        $additional   = $this->getAdditionalQueryData();

        $rowSeparator = reportExtensionP::SEPARATOR_ROWS;
        $colSeparator = reportExtensionP::SEPARATOR_COLS;
        $stageCalc    = stageCalc::create($this->_db, $this->_params['sub']);

        $relevantSelectWhere = "ts.erkrankung_id = t.erkrankung_id AND ts.anlass = t.anlass";

        $relevantSelects = array(
            $stageCalc->select() . "AS 'uicc'",
            "(SELECT
                    IF(
                        MAX(ts.nur_zweitmeinung) IS NOT NULL OR
                        MAX(ts.nur_diagnosesicherung) IS NOT NULL OR
                        MAX(ts.kein_fall) IS NOT NULL,
                        1,
                        NULL
                    )
                FROM tumorstatus ts
                WHERE
                    {$relevantSelectWhere}
                ) AS nicht_zaehlen
                ",
            "(SELECT ts.zufall             FROM tumorstatus ts WHERE {$relevantSelectWhere}                                       ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS zufallsbefund",
            "(SELECT MAX(ts.zufall)        FROM tumorstatus ts WHERE {$relevantSelectWhere}                                                                                                                          LIMIT 1) AS zufall",
            "(SELECT ts.t                  FROM tumorstatus ts WHERE {$relevantSelectWhere} AND LEFT(ts.t, 1) = 'c'               ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS ct",
            "(SELECT ts.n                  FROM tumorstatus ts WHERE {$relevantSelectWhere} AND LEFT(ts.n, 1) = 'c'               ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS cn",
            "(SELECT ts.psa                FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.psa IS NOT NULL                ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS psa",
            "(SELECT ts.eignung_nerverhalt FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.eignung_nerverhalt IS NOT NULL ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS eignung_nerverhalt",
            "(SELECT ts.r_lokal            FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.r_lokal IS NOT NULL            ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS r_lokal",
            "(SELECT ts.l                  FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.l IS NOT NULL                  ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS l",
            "(SELECT ts.v                  FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.v IS NOT NULL                  ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS v",
            "(SELECT ts.ppn                FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.ppn IS NOT NULL                ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS ppn",
            "(SELECT ts.g                  FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.g IS NOT NULL                  ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS g",
            "(SELECT ts.m                  FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.m IS NOT NULL                  ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS m",
            "(SELECT
                    IFNULL(ts.gleason1, 0) + IFNULL(ts.gleason2, 0)
                FROM tumorstatus ts
                WHERE
                    {$relevantSelectWhere} AND
                    (ts.gleason1 IS NOT NULL OR ts.gleason2 IS NOT NULL)
                ORDER BY
                    ts.datum_sicherung DESC,
                    ts.sicherungsgrad ASC,
                    ts.datum_beurteilung DESC
                LIMIT 1) AS 'gleasoncalculated'",
            "(SELECT ts.resektionsrand          FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.resektionsrand IS NOT NULL                          ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS resektionsrand",
            "(SELECT ts.datum_sicherung         FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.rezidiv_psa IS NOT NULL                             ORDER BY ts.datum_sicherung DESC LIMIT 1) AS rezidiv_psa",
            "(SELECT ts.datum_sicherung         FROM tumorstatus ts WHERE {$relevantSelectWhere} AND 1 IN (ts.rezidiv_lokal, rezidiv_lk)                    ORDER BY ts.datum_sicherung DESC LIMIT 1) AS rezidiv",
            "(SELECT ts.datum_sicherung         FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.rezidiv_metastasen IS NOT NULL                      ORDER BY ts.datum_sicherung DESC LIMIT 1) AS metastasen",
            "(SELECT GROUP_CONCAT(DISTINCT CONCAT_WS(',', ts.datum_sicherung, ts.m) SEPARATOR '|') FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.m IS NOT NULL AND (ts.tnm_praefix IS NULL OR ts.tnm_praefix NOT LIKE '%y%' ))         AS 'prae_m'",
            "(SELECT GROUP_CONCAT(DISTINCT CONCAT_WS(',', ts.datum_sicherung, ts.anlass) SEPARATOR '|') FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass LIKE 'r%' ORDER BY ts.datum_sicherung ASC) AS 'rezidiv_datum'",
            "(SELECT
                    IF(
                        ts.lk_entf IS NOT NULL AND ts.lk_entf != '0',
                        1,
                        NULL
                    )
                    FROM tumorstatus ts
                        WHERE {$relevantSelectWhere} AND LEFT(ts.t, 1) = 'p'
                        ORDER BY ts.datum_sicherung DESC, ts.datum_beurteilung DESC LIMIT 1
                ) AS lymphadenektomie",
            "(SELECT
                IF(
                    (ts.lk_bef IS NOT NULL) AND (ts.n = 'pn0' OR ts.n = 'pn1'),
                    1,
                    NULL
                )
                FROM tumorstatus ts
                    WHERE {$relevantSelectWhere} AND LEFT(ts.t, 1) = 'p'
                    ORDER BY ts.datum_sicherung DESC, ts.datum_beurteilung DESC LIMIT 1
            ) AS befund_lk"
        );

        // build pre Query
        $preQuery = $this->_getPreQuery("diagnose = 'C61'", array_merge($relevantSelects, $additional['selects']));

        $relapseReferenceDateQuery = "
            IF(sit.anlass LIKE 'r%' AND (
                    IFNULL(
                        MIN(
                            DISTINCT IF(
                                s.form = 'konferenz_patient' AND
                                LEFT(s.report_param, 4) = 'prae' AND
                                SUBSTRING(s.report_param, 6) != '' AND
                                SUBSTRING(s.report_param, 6) BETWEEN sit.start_date AND sit.end_date,
                                SUBSTRING(s.report_param, 6),
                                NULL
                            )
                        ),
                        MIN(tp.datum)
                    )
                ) IS NULL,
                IF(sit.start_date = '0000-00-00', sit.start_date_rezidiv, sit.start_date),
                IF(sit.anlass LIKE 'r%',
                    IFNULL(
                        MIN(
                            DISTINCT IF(
                                s.form = 'konferenz_patient' AND
                                LEFT(s.report_param, 4) = 'prae' AND
                                SUBSTRING(s.report_param, 6) != '' AND
                                SUBSTRING(s.report_param, 6) BETWEEN sit.start_date AND sit.end_date,
                                SUBSTRING(s.report_param, 6),
                                NULL
                            )
                        ),
                    MIN(tp.datum)
                ),
                null
            )
        )";

        $denoBisphTherapyMedicationIds = $this->_getTherapyMedicationsIds(array('denosumab', 'biphosphonate'));

        $query = "SELECT
            {$additional['fields']}
            sit.nachname                                                               AS 'nachname',
            sit.vorname                                                                AS 'vorname',
            sit.geburtsdatum                                                           AS 'geburtsdatum',
            sit.patient_nr                                                             AS 'patient_nr',
            GROUP_CONCAT(DISTINCT be.user_id SEPARATOR '|')                            AS 'facharzt',
            {$relapseReferenceDateQuery}                                               AS 'bezugsdatum',
            NULL                                                                       AS 'therapie_bezugsdatum',
            sit.diagnose                                                               AS 'diagnose',
            sit.erkrankung_id                                                          AS 'erkrankung_id',
            IF(
                sit.anlass = 'p' AND (
                    COUNT(DISTINCT IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = 1, s.form_id, NULL)) > 0 OR
                    COUNT(DISTINCT th_sys.therapie_systemisch_id) > 0 OR
                    COUNT(DISTINCT th_str.strahlentherapie_id) > 0 OR
                    COUNT(DISTINCT th_son.sonstige_therapie_id) > 0 OR
                    COUNT(DISTINCT IF('1' IN (tp.watchful_waiting, tp.active_surveillance, tp.palliative_versorgung), tp.therapieplan_id, NULL)) > 0
            ), 1, 0)                                                                   AS 'primaerfall',

            sit.zufall                                                                 AS 'zufallsbefund',

            NULL                                                                       AS 'anlass',

            sit.anlass                                                                 AS 'anlass_case',

            sit.datum_sicherung                                                        AS 'datum_sicherung',

            sit.psa                                                                    AS 'gesamt_psa',

            (SELECT a.ics FROM anamnese a WHERE a.erkrankung_id = sit.erkrankung_id AND a.datum BETWEEN sit.start_date AND sit.end_date AND a.ics IS NOT NULL ORDER BY a.datum DESC
            LIMIT 1)                                                                   AS 'ics_score',

            (SELECT a.iciq_ui FROM anamnese a WHERE a.erkrankung_id = sit.erkrankung_id AND a.datum BETWEEN sit.start_date AND sit.end_date AND a.iciq_ui IS NOT NULL ORDER BY a.datum DESC
            LIMIT 1)                                                                   AS 'iciq_ui_gesamtscore_prae',

            (SELECT a.iief5 FROM anamnese a WHERE a.erkrankung_id = sit.erkrankung_id AND a.datum BETWEEN sit.start_date AND sit.end_date AND a.iief5 IS NOT NULL ORDER BY a.datum DESC
            LIMIT 1)                                                                   AS 'iief_5_score_prae',

            (SELECT a.lq_dkg FROM anamnese a WHERE a.erkrankung_id = sit.erkrankung_id AND a.datum BETWEEN sit.start_date AND sit.end_date AND a.lq_dkg IS NOT NULL ORDER BY a.datum DESC
            LIMIT 1)                                                                   AS 'lebensqualitaet_lq_prae',

            (SELECT a.gz_dkg FROM anamnese a WHERE a.erkrankung_id = sit.erkrankung_id AND a.datum BETWEEN sit.start_date AND sit.end_date AND a.gz_dkg IS NOT NULL ORDER BY a.datum DESC
            LIMIT 1)                                                                   AS 'gesundheitszustand_gz_prae',

            GROUP_CONCAT(DISTINCT
                IF(h.art = 'pr' AND (h.stanzen_ges_anz IS NOT NULL OR h.l_anz IS NOT NULL OR h.r_anz IS NOT NULL),
                    CONCAT_WS(',', h.datum, IFNULL(h.stanzen_ges_anz, IFNULL(h.l_anz, 0) + IFNULL(h.r_anz, 0))),
                    NULL
                )
                SEPARATOR '|'
            )                                                                           AS 'anz_stanzzylinder',

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
            1,
            NULL
            )                                                                          AS 'prae_konferenz',

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
            )                                                                        AS 'post_konferenz',

            (SELECT tp.leistungserbringer
            FROM therapieplan tp
            WHERE tp.erkrankung_id = sit.erkrankung_id AND
                tp.datum BETWEEN sit.start_date AND sit.end_date AND
                tp.leistungserbringer IS NOT NULL
            ORDER BY tp.datum ASC
            LIMIT 1)                                                                   AS 'leistungserbringer_raw',

            null                                                                       AS 'leistungserbringer',

            COUNT(
               DISTINCT IF(
                  s.form = 'konferenz_patient' AND
                  LEFT(s.report_param, 4) = 'post' AND
                  SUBSTRING(s.report_param, 6) != '' AND
                  SUBSTRING(s.report_param, 6) BETWEEN sit.start_date AND sit.end_date,
                  s.form_id,
                  NULL
               )
            )                                                                       AS 'post_konferenz_anz',

            IF(
               COUNT(
                  DISTINCT IF(
                     s.form = 'konferenz_patient' AND
                     LEFT(s.report_param, 4) = 'morb' AND
                     SUBSTRING(s.report_param, 6) != '' AND
                     SUBSTRING(s.report_param, 6) BETWEEN sit.start_date AND sit.end_date,
                     s.form_id,
                     NULL
                  )
               ) > 0,
               1, NULL
            )                                                                          AS 'morbiditaetskonferenz',
        ";

        if ($this->getParam('name') !== 'p01') {
            $query .= "SUBSTRING(IF(sit.zufall IS NOT NULL AND sit.ct IS NULL, sit.pt, sit.ct), 2) AS 'zt',
                    SUBSTRING(IF(sit.zufall IS NOT NULL AND sit.cn IS NULL, sit.pn, sit.cn), 2) AS 'zn',";
        }

        // Wird aktuell nur von der p01 wirklich verwendet
        if ($this->getParam('name') !== 'p02') {

            $query .= "
                  IFNULL((SELECT
                        COUNT(DISTINCT kt.user_id)
                    FROM konferenz_patient kp
                        INNER JOIN konferenz_teilnehmer kt ON kt.konferenz_id = kp.konferenz_id AND kt.teilgenommen IS NOT NULL
                           INNER JOIN user kt_user ON kt_user.user_id = kt.user_id AND kt_user.fachabteilung IN('2200', 'Z4700')
                    WHERE
                        kp.erkrankung_id = sit.erkrankung_id AND
                        FIND_IN_SET(kt.konferenz_id, GROUP_CONCAT(DISTINCT kp_post.konferenz_id))
                    GROUP BY
                        kp.erkrankung_id
                    ), 0)                                                                 AS 'kt_user_urologie',

                 IFNULL((SELECT
                        COUNT(DISTINCT kt.user_id)
                    FROM konferenz_patient kp
                        INNER JOIN konferenz_teilnehmer kt ON kt.konferenz_id = kp.konferenz_id AND kt.teilgenommen IS NOT NULL
                           INNER JOIN user kt_user ON kt_user.user_id = kt.user_id AND kt_user.fachabteilung IN('3300', '3305')
                    WHERE
                        kp.erkrankung_id = sit.erkrankung_id AND
                        FIND_IN_SET(kt.konferenz_id, GROUP_CONCAT(DISTINCT kp_post.konferenz_id))
                    GROUP BY
                        kp.erkrankung_id
                    ),0)                                                                 AS 'kt_user_strahlen',

               IFNULL((SELECT
                        COUNT(DISTINCT kt.user_id)
                    FROM konferenz_patient kp
                        INNER JOIN konferenz_teilnehmer kt ON kt.konferenz_id = kp.konferenz_id AND kt.teilgenommen IS NOT NULL
                           INNER JOIN user kt_user ON kt_user.user_id = kt.user_id AND kt_user.fachabteilung IN('0500', '0510', 'Z4700')
                    WHERE
                        kp.erkrankung_id = sit.erkrankung_id AND
                        FIND_IN_SET(kt.konferenz_id, GROUP_CONCAT(DISTINCT kp_post.konferenz_id))
                    GROUP BY
                        kp.erkrankung_id
                    ),0)                                                                 AS 'kt_user_haema',

                IFNULL((SELECT
                        COUNT(DISTINCT kt.user_id)
                    FROM konferenz_patient kp
                        INNER JOIN konferenz_teilnehmer kt ON kt.konferenz_id = kp.konferenz_id AND kt.teilgenommen IS NOT NULL
                           INNER JOIN user kt_user ON kt_user.user_id = kt.user_id AND kt_user.fachabteilung IN('Z4400')
                    WHERE
                        kp.erkrankung_id = sit.erkrankung_id AND
                        FIND_IN_SET(kt.konferenz_id, GROUP_CONCAT(DISTINCT kp_post.konferenz_id))
                    GROUP BY
                        kp.erkrankung_id
                 ),0)                                                                     AS 'kt_user_patho',
              ";
        }

        $query .= "
                MIN(IF(h.art = 'pr', h.datum, NULL))                                       AS 'prae_histo',

                MIN(IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = 1, s.form_date, NULL)) AS 'primaer_op',

                CONCAT_WS(', ',
                    MIN(IF(s.form = 'eingriff' AND LOCATE('1-464.00', SUBSTRING(s.report_param, 5)) != 0, '1-464.00', NULL)),
                    MIN(IF(s.form = 'eingriff' AND LOCATE('1-464.01', SUBSTRING(s.report_param, 5)) != 0, '1-464.01', NULL))
                )                                                                          AS 'biopsien_ops_codes',

                GROUP_CONCAT(DISTINCT
                    IF(s.form = 'eingriff' AND SUBSTRING(s.report_param, 5) != '',
                       SUBSTRING(s.report_param, 5),
                       NULL
                    )
                    SEPARATOR ' '
                )                                                                          AS 'ops_codes',

                MAX(kp_prae.patientenwunsch_nerverhalt)                                    AS 'nerverhalt_gewuenscht',

                sit.eignung_nerverhalt                                                     AS 'geeignet_fuer_nerverhalt_op',

                IF(
                    COUNT(IF(
                       s.form = 'eingriff' AND LEFT(s.report_param, 1) = 1 AND (
                          LOCATE('5-604.1', SUBSTRING(s.report_param, 5)) > 0 OR
                          LOCATE('5-604.3', SUBSTRING(s.report_param, 5)) > 0 OR
                          LOCATE('5-604.5', SUBSTRING(s.report_param, 5)) > 0
                       ),
                       s.form_id,
                       NULL
                    )),
                    1,
                    NULL
                )                                                                         AS 'nerverhaltend_operiert',

                MAX(
                    IF(
                       1 IN (op.art_primaertumor, op.art_rezidiv),
                       (SELECT CONCAT_WS(', ', u.nachname, u.vorname) FROM user u WHERE u.user_id = op.operateur1_id LIMIT 1),
                       NULL
                    )
                )                                                                          AS 'operateur_1',

                MAX(
                    IF(
                       1 IN (op.art_primaertumor, op.art_rezidiv),
                       (SELECT CONCAT_WS(', ', u.nachname, u.vorname) FROM user u WHERE u.user_id = op.operateur2_id LIMIT 1),
                       NULL
                    )
                )                                                                          AS 'operateur_2',

                IF(
                    COUNT(
                        DISTINCT IF(s.form = 'eingriff' AND
                        (LOCATE('5-604', SUBSTRING(s.report_param, 5)) != 0 OR
                        LOCATE('5-576.2', SUBSTRING(s.report_param, 5)) != 0 OR
                        LOCATE('5-576.3', SUBSTRING(s.report_param, 5)) != 0 OR
                        LOCATE('5-576.4', SUBSTRING(s.report_param, 5)) != 0 OR
                        LOCATE('5-576.5', SUBSTRING(s.report_param, 5)) != 0
                        ), s.form_id, NULL)
                    ) > 0,
                    1,
                    NULL
                )                                                                            AS 'rpe_rze',

                IF(
                    COUNT(
                        IF(s.form = 'eingriff' AND LOCATE('5-604', SUBSTRING(s.report_param, 5)) != 0, s.form_id, NULL)
                    ) > 0,
                    1,
                    NULL
                )                                                                            AS 'rpe',

                GROUP_CONCAT(DISTINCT
                    IF(s.form = 'eingriff' AND LOCATE('5-604', SUBSTRING(s.report_param, 5)) != 0,
                        CONCAT_WS('{$colSeparator}', s.form_id, s.form_date),
                        NULL
                    )
                    SEPARATOR '{$rowSeparator}'
                )                                                                            AS 'bezug_rpe',

                GROUP_CONCAT(DISTINCT
                    IF(s.form = 'eingriff' AND LOCATE('5-604', SUBSTRING(s.report_param, 5)) != 0,
                        CONCAT_WS('{$colSeparator}', s.form_id, s.form_date),
                        NULL
                    )
                    SEPARATOR '{$rowSeparator}'
                )                                                                            AS 'bezug_rpe_kompl',

                GROUP_CONCAT(DISTINCT
                     IF(opk.komplikation IN('ane', 'darm', 'Harnver', 'lzb', 'nbl') AND opk.revisionsoperation = 1,
                        CONCAT_WS('{$colSeparator}', opk.datum, opk.eingriff_id),
                        NULL
                     )
                     SEPARATOR '{$rowSeparator}'
                )                                                                            AS 'revisions_op_90',

                IF(COUNT(
                    IF(s.form = 'eingriff' AND
                        (LOCATE('5-576.2', SUBSTRING(s.report_param, 5)) != 0 OR
                        LOCATE('5-576.3', SUBSTRING(s.report_param, 5)) != 0 OR
                        LOCATE('5-576.4', SUBSTRING(s.report_param, 5)) != 0 OR
                        LOCATE('5-576.5', SUBSTRING(s.report_param, 5)) != 0
                        ),
                        s.form_id,
                    NULL)
                    ),
                    1, NULL
                )                                                                            AS 'rze',

                GROUP_CONCAT(
                    IF(s.form = 'eingriff' AND (LOCATE('5-576.2', SUBSTRING(s.report_param, 5)) != 0 OR
                    LOCATE('5-576.3', SUBSTRING(s.report_param, 5)) != 0 OR
                    LOCATE('5-576.4', SUBSTRING(s.report_param, 5)) != 0 OR
                    LOCATE('5-576.5', SUBSTRING(s.report_param, 5)) != 0
                    ),
                        CONCAT_WS('{$colSeparator}', s.form_id, s.form_date),
                        NULL
                     )
                     SEPARATOR '{$rowSeparator}'
                )                                                                            AS 'bezug_rze',

                null                                                                         AS 'radikale_prostatektomie',

                MAX(
                    IF(
                        s.form = 'eingriff' AND s.form_id IN ({$this->_getRPEOpIds()}) AND
                        (LOCATE('5-604', SUBSTRING(s.report_param, 5)) != 0 OR
                        LOCATE('5-576.2', SUBSTRING(s.report_param, 5)) != 0 OR
                        LOCATE('5-576.3', SUBSTRING(s.report_param, 5)) != 0 OR
                        LOCATE('5-576.4', SUBSTRING(s.report_param, 5)) != 0 OR
                        LOCATE('5-576.5', SUBSTRING(s.report_param, 5)) != 0 OR
                        LOCATE('5-576.8', SUBSTRING(s.report_param, 5)) != 0 OR
                        LOCATE('5-576.x', SUBSTRING(s.report_param, 5)) != 0
                        ),
                        s.form_date,
                        NULL
                    )
                )                                                                          AS 'radikale_prostatektomie_date',

                sit.ct                                                                     AS 'ct',

                sit.cn                                                                     AS 'cn',

                sit.pt                                                                     AS 'pt',

                sit.pn                                                                     AS 'pn',

                sit.m                                                                      AS 'm',

                sit.prae_m                                                                 AS 'prae_m',

                ## Beachten: UICC wird am Ende nochmals nachverarbeitet! ##

                sit.uicc                                                                   AS 'uicc',

                IF(
                    sit.g IS NOT NULL,
                    (SELECT bez FROM l_basic WHERE klasse = 'g' AND code = sit.g)
                    ,
                    NULL
                )                                                                          AS 'g',

                sit.r                                                                      AS 'r',

                sit.r_lokal                                                                AS 'r_lokal',

                sit.l                                                                      AS 'l',

                sit.v                                                                      AS 'v',

                sit.ppn                                                                    AS 'ppn',

                sit.gleasoncalculated                                                      AS 'gleason_score',

                IF(COUNT(
                    DISTINCT IF(
                        LEFT(k.komplikation, 2) IN ('wi','wa'),
                        k.komplikation_id,
                        NULL
                    )
                ), 1, NULL)                                                                AS 'wundinfektion',

                GROUP_CONCAT(DISTINCT k.komplikation SEPARATOR '|')                        AS 'aufgetretene_komplikationen',

                IF(COUNT(DISTINCT IF(op.art_revision IS NOT NULL, op.eingriff_id, NULL)) > 0,
                    COUNT(DISTINCT IF(op.art_revision IS NOT NULL, op.eingriff_id, NULL)),
                    COUNT(DISTINCT IF(k.revisionsoperation = '1', k.komplikation_id, NULL))
                )                                                                          AS 'revis_op',

                IF(MAX(tp.strahlen) IS NOT NULL,
                    IF(
                        COUNT(DISTINCT IF(
                           tp.strahlen = '1',
                           tp.therapieplan_id,
                           NULL
                        )) > 0
                        ,
                    1,
                    0
                ), NULL)                                                                   AS 'gepl_strahlenth',

                IF(COUNT(th_str.strahlentherapie_id) > 0, 1, NULL)                         AS 'durchgef_strahlenth',

                MIN(th_str.beginn)                                                         AS 'beginn_str',

                MAX(
                    IF(
                        (DATEDIFF(th_str.ende, th_str.beginn) BETWEEN 0 AND 55) AND th_str.gesamtdosis >= 74 AND th_str.gesamtdosis < 80,
                        1,
                        IF(
                            (DATEDIFF(th_str.ende, th_str.beginn) BETWEEN 0 AND 55) OR
                            (th_str.gesamtdosis >= 74 AND th_str.gesamtdosis < 80),
                            0,
                            NULL
                        )
                    )
                )                                                                          AS 'durchgef_strahlenth_74_80',

                MIN(th_sys.beginn)                                                         AS 'beginn_sys',

                IF(COUNT(
                    DISTINCT IF(
                        th_str.art = 'str_pk',
                        th_str.strahlentherapie_id,
                        NULL
                    )
                ), 1, NULL)                                                                AS 'perkutane_str',

                IF(COUNT(
                    DISTINCT IF(
                        th_str.art = 'str_pk' AND
                        th_str.gesamtdosis >= 70,
                        th_str.strahlentherapie_id,
                        NULL
                    )) > 0,
                    1,
                    IF(
                        COUNT(DISTINCT IF(
                           th_str.art = 'str_pk' AND
                           th_str.gesamtdosis < 70,
                           th_str.strahlentherapie_id,
                           NULL
                        )) > 0,
                        0,
                        NULL
                    )
                )                                                                          AS 'perkutane_str_min_70_gy',

                MIN(IF(
                    th_str.art = 'str_seeds' OR th_str.art = 'str_ldr',
                    th_str.beginn,
                    NULL
                ))                                                                         AS 'str_permanent_seed_beginn',

                IF(
                    COUNT(IF(
                        th_str.art = 'str_seeds' OR th_str.art = 'str_ldr',
                        th_str.strahlentherapie_id,
                        NULL
                    )),
                    1,
                    NULL
                )                                                                          AS 'str_permanent_seed',

                GROUP_CONCAT(DISTINCT
                    IF(
                        th_str.art = 'str_seeds' OR th_str.art = 'str_ldr',
                        CONCAT_WS('{$colSeparator}', th_str.strahlentherapie_id, th_str.beginn, IFNULL(th_str.therapieplan_id, '')),
                        NULL
                    )
                    SEPARATOR '{$rowSeparator}'
                )                                                                          AS 'bezug_seed',

                IF(COUNT(
                    DISTINCT IF(
                        (th_str.art = 'str_seeds' OR th_str.art = 'str_ldr') AND
                        th_str.seed_strahlung_90d > 130,
                        th_str.strahlentherapie_id,
                        NULL
                    )) > 0,
                    1,
                    IF(COUNT(
                        DISTINCT IF(
                            (th_str.art = 'str_seeds' OR th_str.art = 'str_ldr') AND
                            th_str.seed_strahlung_90d <= 130,
                            th_str.strahlentherapie_id,
                            NULL
                        )) > 0,
                        0,
                        NULL
                    )
                )                                                                              AS 'str_permanent_seed_d90_130_gy',

                MIN(IF(
                    th_str.art = 'str_hdr',
                    th_str.beginn,
                    NULL
                ))                                                                             AS 'hdr_brachytherapie_beginn',

                IF(
                    COUNT(IF(
                        th_str.art = 'str_hdr',
                        th_str.strahlentherapie_id,
                        NULL
                    )),
                    1,
                    NULL
                )                                                                              AS 'hdr_brachytherapie',

                GROUP_CONCAT(DISTINCT
                    IF(
                        th_str.art = 'str_hdr',
                        CONCAT_WS('{$colSeparator}', th_str.strahlentherapie_id, th_str.beginn, IFNULL(th_str.therapieplan_id, '')),
                        NULL
                    )
                    SEPARATOR '{$rowSeparator}'
                )                                                                             AS 'bezug_hdr',

                IF(MAX(tp.chemo) IS NOT NULL,
                IF(COUNT(
                    DISTINCT IF(
                        tp.chemo = '1',
                        tp.therapieplan_id,
                        NULL
                    )) > 0,
                    1,
                    0
                ), NULL)                                                                      AS 'gepl_chemoth',

                IF(COUNT(
                    DISTINCT IF(
                        th_sys.vorlage_therapie_art IN ('c','ci','cst'),
                        th_sys.therapie_systemisch_id,
                        NULL
                    )
                ), 1, NULL)                                                                   AS 'durchgef_chemoth',

                IF(MAX(tp.immun)IS NOT NULL,
                    IF(COUNT(
                        DISTINCT IF(
                            tp.immun = '1',
                            tp.therapieplan_id,
                            NULL
                        )) > 0,
                        1,
                        0
                    ),
                NULL)                                                                          AS 'gepl_immunth',

                IF(COUNT(
                    DISTINCT IF(
                        th_sys.vorlage_therapie_art IN ('ci','ist', 'i'),
                        th_sys.therapie_systemisch_id,
                        NULL
                    )
                ), 1, NULL)                                                                AS 'durchgef_immunth',

                IF(MAX(tp.ah) IS NOT NULL,
                    IF(COUNT(
                        DISTINCT IF(
                            tp.ah = '1',
                            tp.therapieplan_id,
                            NULL
                        )
                    ) > 0,
                    1,
                    0
                ), NULL)                                                                   AS 'gepl_antih_th',

                IF(COUNT(
                    IF(
                        th_sys.vorlage_therapie_art IN('ah', 'ahst'),
                        th_sys.therapie_systemisch_id,
                        NULL
                    )) > 0,
                1,
                NULL)                                                                        AS 'durchgef_antihth',

                IF(COUNT(
                    IF(
                        th_sys.vorlage_therapie_art IN('ah', 'ahst') AND
                        th_sys.intention IN('kura', 'pala', 'kurna', 'palna'),
                        th_sys.therapie_systemisch_id,
                        NULL
                    )) > 0,
                1,
                NULL)                                                                        AS 'durchgef_adj_antihth',

                IF(COUNT(
                    IF(
                        th_str.intention = 'kur',
                        th_str.strahlentherapie_id,
                        NULL
                    )) > 0,
                    1,
                    NULL
                )                                                                            AS 'def_strahlentherapie',

                null                                                                         AS 'post_tumorfrei_365_tage',

                IF (
                    COUNT(IF(
                        th_str.intention = 'kur' AND th_str.art = 'str_pk',
                        th_str.strahlentherapie_id,
                        NULL
                    )),
                    1,
                    NULL
                )                                                                            AS 'def_perkutane_strahlentherapie',

                MIN(
                    IF((th_str.intention = 'kur' AND th_str.art = 'str_pk'),
                        th_str.beginn,
                        NULL
                    )
                )                                                                            AS 'def_perkutane_str_beginn',

                GROUP_CONCAT(DISTINCT
                    IF(
                        (th_str.intention = 'kur' AND th_str.art = 'str_pk'),
                        CONCAT_WS('{$colSeparator}', th_str.strahlentherapie_id, th_str.beginn, IFNULL(th_str.therapieplan_id, '')),
                        NULL
                    )
                    SEPARATOR '{$rowSeparator}'
                )                                                                            AS 'bezug_perk',

                {$this->_buildQueryPallRadio()}                                              AS 'pall_strahlenth',

                GROUP_CONCAT(DISTINCT
                    IF(
                        th_son.sonstige_art IS NOT NULL,
                        th_son.sonstige_art,
                        NULL
                    )
                    SEPARATOR '|'
                )                                                                            AS 'sonstige_therapie_art',

                {$this->_buildQueryPallSupply()}                                             AS 'pall_vers',

                {$this->_buildQueryActiveSurveillance()}                                     AS 'active_surveillance',

                {$this->_buildQueryActiveSurveillance()}                                     AS 'bezug_as',

                {$this->_buildQueryWatchfulWaiting()}                                        AS 'watchful_waiting',

                {$this->_buildQueryWatchfulWaiting()}                                        AS 'bezug_ww',

                MAX(
                    IF(
                        b.psychoonkologie IS NOT NULL,
                        IF(
                            b.psychoonkologie = 0,
                            b.psychoonkologie,
                            IF (b.psychoonkologie_dauer >= 25, 1, 0)
                        ),
                    NULL
                    )
                )                                                                            AS 'psychoonkologische_betreuung',

                MAX(b.sozialdienst)                                                          AS 'sozialdienst',

                IF(COUNT(
                    IF(
                        s.form = 'fragebogen' AND s.report_param IN ({$this->_getPreSelect('zufrFragebogen')}),
                        s.form_id,
                        NULL
                        )
                    ) > 0,
                    1,
                    NULL
                )                                                              AS 'befragungsbogen',

                MAX(b.interdisziplinaer_angeboten)                             AS 'interdisziplinaer_angeboten',

                GROUP_CONCAT(DISTINCT
                    IF(s.form = 'studie',
                        CONCAT_WS('|', s.form_id, DATE_FORMAT(s.report_param, '%d.%m.%Y')),
                        NULL
                    )
                    SEPARATOR ', '
                )                                                              AS 'datum_studie',

                COUNT(DISTINCT IF(s.form = 'studie', s.form_id, NULL))         AS 'count_studie',

                MAX(n.datum)                                                   AS 'letzte_nachsorge',

                GROUP_CONCAT(
                    DISTINCT DATE_FORMAT(n.datum, '%d.%m.%Y')
                    SEPARATOR ', '
                )                                                              AS 'datum_nachsorge',

                GROUP_CONCAT(DISTINCT n.datum SEPARATOR '{$rowSeparator}')     AS 'aftercare_dates',

                sit.rezidiv_psa                                                AS 'psa_rezidiv',

                sit.rezidiv                                                    AS 'rezidiv',

                sit.metastasen                                                 AS 'metastasen',
                sit.rezidiv_datum                                              AS 'rezidiv_datum',

                IF(
                    MAX(x.abschluss_grund) IS NOT NULL AND MAX(x.abschluss_grund) = 'lost',
                    1,
                    IF(
                       MAX(x.abschluss_grund) IS NOT NULL AND MAX(x.abschluss_grund) != 'lost',
                       0,
                       NULL
                    )
                )                                                               AS 'lost_to_fu',

                MAX(x.todesdatum)                                               AS 'todesdatum',

                sit.morphologie                                                 AS 'morphologie',

                IF(
                    COUNT(
                       IF(
                          s.form = 'eingriff' AND
                          LOCATE('3-05c.0', SUBSTRING(s.report_param, 5)) > 0,
                          s.form_id,
                          NULL
                       )
                    ) > 0 OR
                    COUNT(
                       IF(
                          u.art = '3-05c.0',
                          u.untersuchung_id,
                          NULL
                       )
                    ) > 0,
                    1,
                    NULL
                )                                                               AS 'trus',

                GROUP_CONCAT(DISTINCT
                     IF(h.art = 'pr' AND {$this->_checkSwage()},
                         CONCAT_WS(',', h.datum, {$this->_countSwage()}),
                         NULL
                     )
                     SEPARATOR '|'
                )                                                              AS 'stanzzylinder_1_cm',

                GROUP_CONCAT(DISTINCT
                     IF(h.art = 'pr' AND {$this->_checkSwagePositive()},
                         CONCAT_WS(',', h.datum, {$this->_countSwagePositive()}),
                         NULL
                     )
                     SEPARATOR '|'
                )                                                              AS 'stanzzylinder_positiv',

                GROUP_CONCAT(DISTINCT
                     IF(th_str.art = 'str_seeds' AND th_str.gesamtdosis IS NOT NULL,
                         CONCAT_WS(',', th_str.beginn, th_str.gesamtdosis),
                         NULL
                     )
                     SEPARATOR '|'
                )                                                              AS 'dosis_permanente_seeds',

                sit.resektionsrand                                             AS 'resektionsrand',

                IF(COUNT(
                    IF(
                       th_str.intention IN ('kura', 'pala'),
                       th_str.strahlentherapie_id,
                       NULL
                    )
                ), 1, NULL)                                                       AS 'durchgef_adj_strahlenth',

                GROUP_CONCAT(DISTINCT
                    IF(h.eingriff_id IN ({$this->_getBiopsyOpIds()}) AND h.art = 'pr',
                        CONCAT_WS('{$colSeparator}', h.histologie_id, h.eingriff_id, IFNULL(h.gleason1, ''), IFNULL(h.gleason2, '')),
                        NULL
                    )
                    SEPARATOR '{$rowSeparator}'
                )                                                                 AS 'befundbericht_stanzen',

                sit.lymphadenektomie                                              AS 'lymphadenektomie',
                sit.befund_lk                                                     AS 'befund_lk',

                IF(COUNT(DISTINCT th_sys.therapie_systemisch_id) = 0 AND
                    COUNT(DISTINCT th_str.strahlentherapie_id) = 1 AND
                    COUNT(DISTINCT IF(th_str.art = 'str_ldr', th_str.strahlentherapie_id, NULL)
                ), 1, NULL)                                                    AS 'ldr_monotherapie',

                null                                                           AS 'zn_rpe',

                null                                                           AS 'psa_rpe',

                sit.start_date                                                 AS 'sit_start_date',

                IF(COUNT(DISTINCT
                    IF(
                        th_sys.vorlage_therapie_id IN ({$denoBisphTherapyMedicationIds}),
                        th_sys.therapie_systemisch_id,
                        NULL
                    )
                ), 1, NULL)                                                                   AS 'sys_medication',

                GROUP_CONCAT(DISTINCT
                    IF(
                        th_sys.vorlage_therapie_id IN ({$denoBisphTherapyMedicationIds}),
                        CONCAT_WS(',', th_sys.beginn, IFNULL(th_sys.zahnarzt, 0)),
                        NULL
                    )
                    SEPARATOR '|'
                )                                                                           AS 'zahn_untersuchung',

                GROUP_CONCAT(DISTINCT
                     IF(k.clavien_dindo IS NOT NULL,
                         CONCAT_WS(',', k.datum, k.clavien_dindo),
                         NULL
                     )
                     SEPARATOR '|'
                )                                                                           AS 'clavien_dindo',

                GROUP_CONCAT(DISTINCT
                     IF(k.ctcae IS NOT NULL,
                         CONCAT_WS(',', k.datum, k.ctcae),
                         NULL
                     )
                     SEPARATOR '|'
                )                                                                           AS 'ctcae',

                null                                                                        AS 'cpz_niedriges_risiko',

                null                                                                        AS 'cpz_mittleres_risiko',

                null                                                                        AS 'cpz_hohes_risiko',

                null                                                                        AS 'cpz_lokal_fortgeschritten',

                null                                                                        AS 'cpz_fortgeschritten',

                null                                                                        AS 'cpz_metastasiert',

                {$this->_buildQueryOtherTherapies(array('OLT', 'HIFU'))}                    AS 'cpz_andere_lokale_therapie',

                {$this->_buildQuerySystemicTherapies(array('son', 'ah', 'c', 'ci', 'i'))}   AS 'cpz_ausschliesslich_systemisch',

                IF (
                    CHAR_LENGTH({$this->_buildQueryOtherTherapies(array('ST', 'CYRO', 'HYPER', 'OT'))}) > 0 ||
                    CHAR_LENGTH({$this->_buildQueryPallRadio()}) > 0 ||
                    {$this->_buildQueryPallSupply()} = 1 ||
                    CHAR_LENGTH({$this->_buildQuerySystemicTherapies(array('sonstr', 'cst', 'ist', 'ahst', 'schmerz'))}) > 0 ||
                    CHAR_LENGTH({$this->_buildQueryRadioTherapies(array('sonstr', 'cst', 'ist', 'ahst', 'schmerz'))}) > 0,
                    1,
                    NULL -- must be null!!! very important
                )                                                                           AS 'cpz_andere_behandlung',

                null                                                                        AS 'cpz_ges',

                {$this->_buildQueryOtherTherapies(array('OLT', 'HIFU'))}                    AS 'bezugsdatum_cpz_andere_lokale_therapie',

                {$this->_buildQuerySystemicTherapies(array('son', 'ah', 'c', 'ci', 'i'))}   AS 'bezugsdatum_cpz_ausschliesslich_systemisch',

                -- other treatment block

                null                                                                        AS 'bezugsdatum_cpz_andere_behandlung',

                {$this->_buildQueryOtherTherapies(array('ST', 'CYRO', 'HYPER', 'OT'))}      AS 'bezugsdatum_cpz_andere_behandlung_andere_behandlung',

                {$this->_buildQueryPallRadio()}                                             AS 'bezugsdatum_cpz_andere_behandlung_pall_strahlentherapie',

                {$this->_buildQuerySystemicTherapies(array('sonstr', 'cst', 'ist', 'ahst', 'schmerz'))} AS 'bezugsdatum_cpz_andere_behandlung_therapie_systemisch',

                {$this->_buildQueryRadioTherapies(array('sonstr', 'cst', 'ist', 'ahst', 'schmerz'))} AS 'bezugsdatum_cpz_andere_behandlung_strahlentherapie',

                GROUP_CONCAT(DISTINCT
                    IF(
                        tp.palliative_versorgung = '1',
                        CONCAT_WS('{$colSeparator}', tp.datum, tp.palliative_versorgung),
                        NULL
                    )
                    SEPARATOR '{$rowSeparator}'
                )                                                                           AS 'bezugsdatum_cpz_andere_behandlung_palliative_versorgung',

                {$this->_buildQuerySystemicTherapies(array('sonstr', 'cst', 'ist', 'ahst', 'schmerz'))} AS 'therapie_systemisch',

                {$this->_buildQueryRadioTherapies(array('sonstr', 'cst', 'ist', 'ahst', 'schmerz'))}    AS 'strahlentherapie',

                IF(
                     sit.anlass = 'p' AND (
                     COUNT(DISTINCT IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = 1, s.form_id, NULL)) = 0 AND
                     COUNT(DISTINCT th_sys.therapie_systemisch_id) = 0 AND
                     COUNT(DISTINCT th_str.strahlentherapie_id) = 0 AND
                     COUNT(DISTINCT th_son.sonstige_therapie_id) = 0 AND
                     COUNT(DISTINCT IF('1' IN (tp.watchful_waiting, tp.active_surveillance, tp.palliative_versorgung), tp.therapieplan_id, NULL)) = 0
                ), 1, 0)                                                              AS 'nz',

                sit.g                                                                 AS 'g_original'
            FROM ($preQuery) sit
                 {$this->_innerStatus()}
                 {$this->_statusJoin('histologie h')}
                 {$this->_statusJoin('untersuchung u')}

                 LEFT JOIN konferenz_patient kp_prae ON s.form = 'konferenz_patient' AND
                                                        kp_prae.konferenz_patient_id = s.form_id AND
                                                        LEFT(s.report_param, 4) = 'prae' AND
                                                        SUBSTRING(s.report_param, 6) != '' AND
                                                        SUBSTRING(s.report_param, 6) BETWEEN sit.start_date AND sit.end_date
        ";

        // do not add "konferenz_patient" join if report is started from p02
        if ($this->getParam('name') !== 'p02') {
            $query .= "
                LEFT JOIN konferenz_patient kp_post ON s.form = 'konferenz_patient' AND
                                                       kp_post.konferenz_patient_id = s.form_id AND
                                                       LEFT(s.report_param, 4) = 'post' AND
                                                       SUBSTRING(s.report_param, 6) != '' AND
                                                       SUBSTRING(s.report_param, 6) BETWEEN sit.start_date AND sit.end_date
            ";
        }

        $query .= "
                {$this->_statusJoin('eingriff op')}

                LEFT JOIN komplikation opk ON opk.erkrankung_id = op.erkrankung_id AND
                                              opk.eingriff_id = op.eingriff_id

                {$this->_statusJoin('komplikation k')}
                {$this->_statusJoin('strahlentherapie th_str')}
                {$this->_statusJoin('therapie_systemisch th_sys')}
                {$this->_statusJoin('sonstige_therapie th_son')}
                {$this->_statusJoin('beratung b')}

                LEFT JOIN tumorstatus tumor ON tumor.erkrankung_id = sit.erkrankung_id

                LEFT JOIN therapieplan tp   ON s.form = 'therapieplan' AND
                                               tp.therapieplan_id = s.form_id AND
                                               (tp.org_id IS NULL OR tp.org_id = '{$this->getParam('org_id')}')

                LEFT JOIN nachsorge n       ON s.form = 'nachsorge' AND
                                               LOCATE(CONCAT_WS('','-',sit.erkrankung_id,'-'), s.report_param) > 0 AND
                                               n.nachsorge_id = s.form_id

                LEFT JOIN aufenthalt auf    ON auf.patient_id = sit.patient_id AND
                                               auf.fachabteilung IN ('2200', '3300', '3305')

                LEFT JOIN behandler be      ON be.patient_id = sit.patient_id AND
                                               be.funktion = 'fach'

                LEFT JOIN abschluss x       ON s.form = 'abschluss' AND
                                               x.abschluss_id = s.form_id

                {$additional['joins']}
            WHERE
                {$this->_getNcState()}
            GROUP BY
                sit.patient_id,
                sit.erkrankung_id,
                sit.anlass
            HAVING
                1
                {$this->_getNcState('p01')}
                {$additional['condition']}
            ORDER BY
                nachname, vorname, bezugsdatum
        ";

        $data = sql_query_array($this->_db, $query);

        // post process all data
        foreach ($data as $i => &$record) {

            // attention!: reference dates for pz01 must called at first, because later field modifications could cause errors
            $referenceData = $this->_buildReferenceData($record);

            // set dates and therapy of reference date to record
            foreach ($referenceData as $fieldName => $fieldValue) {
                $record[$fieldName] = $fieldValue;
            }

            // manually check date range - if false, delete entry and go to next record
            if ($this->_inDateRange($record) === false) {
                unset($data[$i]);
                continue;
            }

            // map situation value
            $record['anlass'] = $this->_getCoding('tumorstatus_anlass', $record['anlass_case']);

            // active surveillance and watchful waiting
            $asWw = $this->_buildActiveSurveillanceAndWatchfulWaiting($record);

            $record['active_surveillance'] = $asWw['active_surveillance'] !== null ? '1' : null;
            $record['watchful_waiting']    = $asWw['watchful_waiting'] !== null    ? '1' : null;

            //UICC
            $record['uicc'] = $stageCalc->calc($record['uicc']);

            // build op codes without (1-464.0 and 1-464.01)
            $record['ops_codes'] = $this->_buildOpCodes($record['ops_codes']);

            // build relapse date
            $record['rezidiv_datum'] = $this->_buildRelapseDate($record['rezidiv_datum']);

            // build complications
            $record['aufgetretene_komplikationen'] = $this->_buildComplications($record['aufgetretene_komplikationen']);

            // build prae therapy m value
            $record['prae_m'] = $this->_buildPraeM($record);

            // build other therapy field
            $record['sonstige_therapie_art'] = $this->_buildOtherTherapies($record['sonstige_therapie_art']);

            // build revision op after 90 days
            $record['revisions_op_90'] = $this->_buildRevisionOpAfter90Days($record);

            // build specialist
            $record['facharzt'] = $this->_buildSpecialist($record['facharzt']);

            // leistungserbringer
            if ($record['leistungserbringer_raw'] !== null && array_key_exists($record['leistungserbringer_raw'], $config) === true) {
                $record['leistungserbringer'] = $config[$record['leistungserbringer_raw']];
            }

            // check tumor free (1 year)
            if ($this->_isOneYearTumorFree($record) === true) {
                $record['post_tumorfrei_365_tage'] = '1';
            }

            // build swage findings
            $record['befundbericht_stanzen'] = $this->_buildSwageFindings($record);

            // build previous RPE
            $record['zn_rpe'] = $this->_buildPreviousRPE($record['erkrankung_id'], $record['sit_start_date']);

            // build psa relapse and str after rpe
            $record['psa_rpe'] = $this->_buildPsaRPE($record);

            // build teeth investigation
            $record['zahn_untersuchung'] = $this->_buildTeethInvestigation($record['zahn_untersuchung']);

            // build dindo fields
            $clavien = $this->_buildClavienDindo($record);

            $record['clavien_dindo'] = $clavien['value'];

            // build ctcae fields
            $ctcae = $this->_buildCtcae($record);

            $record['ctcae'] = $ctcae['value'];

            // build cpz conditions
            $record['cpz_niedriges_risiko']           = $this->_cpz_niedriges_risiko($record);
            $record['cpz_mittleres_risiko']           = $this->_cpz_mittleres_risiko($record);
            $record['cpz_hohes_risiko']               = $this->_cpz_hohes_risiko($record);
            $record['cpz_lokal_fortgeschritten']      = $this->_cpz_lokal_fortgeschritten($record);
            $record['cpz_fortgeschritten']            = $this->_cpz_fortgeschritten($record);
            $record['cpz_metastasiert']               = $this->_cpz_metastasiert($record);
            $record['cpz_andere_lokale_therapie']     = $this->_cpz_andere_lokale_therapie($record);
            $record['cpz_ausschliesslich_systemisch'] = $this->_cpz_ausschliesslich_systemisch($record);
            $record['cpz_andere_behandlung']          = $this->_cpz_andere_behandlung($record);
            $record['cpz_ges']                        = $this->_cpz_ges($record);

            // convert some fields
            $record['datum_studie']           = $this->_removeIdentifier($record['datum_studie']);
            $record['dosis_permanente_seeds'] = $this->_selectMaxByDate($record['dosis_permanente_seeds']);
            $record['stanzzylinder_1_cm']     = $this->_selectMinByDate($record['stanzzylinder_1_cm']);
            $record['stanzzylinder_positiv']  = $this->_selectMinByDate($record['stanzzylinder_positiv']);
            $record['anz_stanzzylinder']      = $this->_selectMinByDate($record['anz_stanzzylinder']);

            // mark fields as 1 if relational fields are filled
            $record['pall_strahlenth']                = strlen($record['pall_strahlenth']) > 0              ? '1' : null;
            $record['radikale_prostatektomie']        = strlen($record['radikale_prostatektomie_date']) > 0 ? '1' : null;
        }

        return $data;
    }


    /**
     * _inDateRange
     * (check manually date rage)
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _inDateRange(array $record)
    {
        static $from;
        static $until;

        // cache
        if ($from === null) {
            $from = $this->_getFromDate();
        }

        // cache
        if ($until === null) {
            $until = $this->_getUntilDate();
        }

        $referenceDate = $record['bezugsdatum'];

        // check start and end date
        if (($from !== null && $referenceDate < $from) || ($until !== null && $referenceDate > $until)) {
            return false;
        }

        return true;
    }


    /**
     * _getBiopsyOpIds
     * (collect all eingriff_id's with biopsy)
     *
     * @access  protected
     * @return  string
     */
    protected function _getBiopsyOpIds()
    {
        $ids = dlookup(
            $this->getDB(),
            'eingriff_ops',
            'GROUP_CONCAT(DISTINCT eingriff_id)',
            "erkrankung_id IN ({$this->getFilteredDiseases()}) AND prozedur IN ('1-464.00', '1-464.01')"
        );

        $ids = strlen($ids) > 0 ? $ids : '0';

        return $ids;
    }


    /**
     * _getRPEOpIds
     *
     * @access  protected
     * @return  string
     */
    protected function _getRPEOpIds()
    {
        $ids = dlookup(
            $this->getDB(),
            'eingriff',
            'GROUP_CONCAT(DISTINCT eingriff_id)',
            "erkrankung_id IN ({$this->getFilteredDiseases()}) AND (org_id = {$this->getParam('org_id')} OR org_id IS NULL)"
        );

        $ids = strlen($ids) > 0 ? $ids : '0';

        return $ids;
    }


    /**
     * _getTherapyMedicationsIds
     *
     * @access  protected
     * @param   array $medications
     * @return  string
     */
    protected function _getTherapyMedicationsIds(array $medications)
    {
        $ids = dlookup($this->_db,
            'vorlage_therapie_wirkstoff',
            'IFNULL(GROUP_CONCAT(DISTINCT vorlage_therapie_id), 0)',
            "(wirkstoff IN ('" . implode("','", $medications) . "'))"
        );

        $ids = strlen($ids) > 0 ? $ids : '0';

        return $ids;
    }


    /**
     * _buildOpCodes
     *
     * @access  protected
     * @param   string  $codes
     * @return  string
     */
    protected function _buildOpCodes($codes)
    {
        if (strlen($codes) > 0) {
            $opsCodes = explode(' ', $codes);
            $opsCodes = array_unique($opsCodes);

            foreach ($opsCodes as $opIndex => $opsCode) {
                if ($opsCode == '1-464.00' || $opsCode == '1-464.01') {
                    unset($opsCodes[$opIndex]);
                }
            }

            $codes = implode(' ', $opsCodes);
        }

        return $codes;
    }


    /**
     * _buildPraeM
     *
     * @access  protected
     * @param   array $record
     * @return  string
     */
    protected function _buildPraeM(array $record)
    {
        $pm    = null;
        $praeM = $record['prae_m'];

        // process only if m values exists
        if (strlen($praeM) > 0) {
            $praeM = explode('|', $record['prae_m']);
            $sort  = array();

            foreach ($praeM as $dateM) {
                list($date, $m) = explode(',', $dateM);

                if ((strlen($record['primaer_op']) === 0 || $date < $record['primaer_op']) &&
                    (strlen($record['beginn_str']) === 0 || $date < $record['beginn_str']) &&
                    (strlen($record['beginn_sys']) === 0 || $date < $record['beginn_sys'])
                ) {
                    $sort[$date] = $m;
                }
            }

            // get latest m value
            if (count($sort) > 0) {
                krsort($sort);

                $pm = reset($sort);
            }
        }

        return $pm;
    }


    /**
     * _buildQueryPallSupply
     *
     * @access  protected
     * @return  string
     */
    protected function _buildQueryPallSupply()
    {
        $query = "MAX(tp.palliative_versorgung)";

        return $query;
    }


    /**
     * _buildQueryPallRadio
     *
     * @access  protected
     * @return  string
     */
    protected function _buildQueryPallRadio()
    {
        $colSeparator = reportExtensionP::SEPARATOR_COLS;
        $rowSeparator = reportExtensionP::SEPARATOR_ROWS;

        $query = "
            GROUP_CONCAT(DISTINCT
                IF(
                    th_str.intention IN ('pal', 'pala', 'palna'),
                    CONCAT_WS('{$colSeparator}', th_str.strahlentherapie_id, th_str.beginn, IFNULL(th_str.therapieplan_id, '')),
                    NULL
                )
                SEPARATOR '{$rowSeparator}'
            )
        ";

        return $query;
    }


    /**
     * _buildQueryOtherTherapies
     *
     * @access  protected
     * @param   array $therapies
     * @return  string
     */
    protected function _buildQueryOtherTherapies(array $therapies)
    {
        $colSeparator = reportExtensionP::SEPARATOR_COLS;
        $rowSeparator = reportExtensionP::SEPARATOR_ROWS;

        $query = "
            GROUP_CONCAT(DISTINCT
                IF(
                    th_son.sonstige_art IN ('" . implode("','", $therapies) . "'),
                    CONCAT_WS('{$colSeparator}', th_son.sonstige_therapie_id, th_son.beginn, IFNULL(th_son.therapieplan_id, '')),
                    NULL
                )
                SEPARATOR '{$rowSeparator}'
            )
        ";

        return $query;
    }


    /**
     * _buildQuerySystemicTherapies
     *
     * @access  protected
     * @param   array $therapies
     * @return  string
     */
    protected function _buildQuerySystemicTherapies(array $therapies)
    {
        $colSeparator = reportExtensionP::SEPARATOR_COLS;
        $rowSeparator = reportExtensionP::SEPARATOR_ROWS;

        $query = "
            GROUP_CONCAT(DISTINCT
                IF(
                    th_sys.vorlage_therapie_art IN ('" . implode("','", $therapies) . "'),
                    CONCAT_WS('{$colSeparator}', th_sys.therapie_systemisch_id, th_sys.beginn, IFNULL(th_sys.therapieplan_id, '')),
                    NULL
                )
                SEPARATOR '{$rowSeparator}'
            )
        ";

        return $query;
    }


    /**
     * _buildQueryRadioTherapies
     *
     * @access  protected
     * @param   array $therapies
     * @return  string
     */
    protected function _buildQueryRadioTherapies(array $therapies)
    {
        $colSeparator = reportExtensionP::SEPARATOR_COLS;
        $rowSeparator = reportExtensionP::SEPARATOR_ROWS;

        $query = "
            GROUP_CONCAT(DISTINCT
                IF(
                    th_str.vorlage_therapie_art IN ('" . implode("','", $therapies) . "'),
                    CONCAT_WS('{$colSeparator}', th_str.strahlentherapie_id, th_str.beginn, IFNULL(th_str.therapieplan_id, '')),
                    NULL
                )
                SEPARATOR '{$rowSeparator}'
            )
        ";

        return $query;
    }


    /**
     * _buildQueryActiveSurveillance
     *
     * @access  protected
     * @return  string
     */
    protected function _buildQueryActiveSurveillance()
    {
        $rowSeparator = reportExtensionP::SEPARATOR_ROWS;

        $query = "
            GROUP_CONCAT(DISTINCT
                IF(tp.active_surveillance = 1, tp.datum, NULL)
                SEPARATOR '{$rowSeparator}'
            )
        ";

        return $query;
    }


    /**
     * _buildQueryWatchfulWaiting
     *
     * @access  protected
     * @return  string
     */
    protected function _buildQueryWatchfulWaiting()
    {
        $rowSeparator = reportExtensionP::SEPARATOR_ROWS;

        $query = "
            GROUP_CONCAT(DISTINCT
                IF(tp.watchful_waiting = 1, tp.datum, NULL)
                SEPARATOR '{$rowSeparator}'
            )
        ";

        return $query;
    }


    /**
     * _buildSpecialist
     *
     * @access  protected
     * @param   string $value
     * @return  string
     */
    protected function _buildSpecialist($value)
    {
        $specialist = null;

        // only process if value is filled
        if (strlen($value) > 0) {
            $tmp    = array();
            $update = false;
            $cache  = $this->getCache('specialist');

            // initialize cache if not already done
            if ($cache === null) {
                $cache  = array();
                $update = true;
            }

            $specialists = explode('|', $value);

            // get user names
            foreach ($specialists as $userId) {
                if (array_key_exists($userId, $cache) === false) {
                    $cache[$userId] = dlookup($this->_db, 'user', "CONCAT_WS(', ', nachname, vorname)", "user_id = '{$userId}'");
                    $update = true;
                }

                $tmp[] = $cache[$userId];
            }

            if ($update === true) {
                $this->setCache('specialist', $cache);
            }

            // remove empty names
            $tmp = array_filter($tmp);

            // process only if names exist
            if (count($tmp) > 0) {
                asort($tmp);

                $specialist = implode('; ', $tmp);
            }
        }

        return $specialist;
    }


    /**
     * _buildTeethInvestigation
     *
     * @access  protected
     * @param   string  $therapies
     * @return  string
     */
    protected function _buildTeethInvestigation($therapies)
    {
        $investigation = null;

        if ($therapies !== null) {
            $therapies = explode('|', $therapies);

            $sort = array();

            // map therapies for sorting
            foreach ($therapies as $therapy) {
                list($date, $done) = explode(',', $therapy);

                $sort[$date][] = (int) $done;
            }

            ksort($sort);

            $firstTherapy = array_shift($sort);

            // check if investigation was done on first therapy
            if (array_sum($firstTherapy) > 0) {
                $investigation = '1';
            }
        }

        return $investigation;
    }


    /**
     * _buildRelapseDate
     *
     * @access  protected
     * @param   string $dates
     * @return  string
     */
    protected function _buildRelapseDate($dates)
    {
        $relapseDate = null;

        // build only if relapses exists
        if (strlen($dates) > 0) {
            $dates = explode('|', $dates);
            $causes  = array();

            foreach ($dates as $dateSituation) {
                $causes[substr($dateSituation, 11)] = date("d.m.Y", strtotime(substr($dateSituation, 0, 10)));
            }

            $relapseDate = implode(', ', $causes);
        }

        return $relapseDate;
    }


    /**
     * _buildPreviousRPE
     *
     * @access  protected
     * @param   string  $diseaseId
     * @param   string  $startDate
     * @return  string
     */
    protected function _buildPreviousRPE($diseaseId, $startDate)
    {
        $primaryRPEOps = $this->getCache('primaryRPEOps');
        $previousRPE   = null;

        // build primary rpe op cache
        if ($primaryRPEOps === null) {

            // needs extra query for 'z.N. RPE' (out of case range)
            $preRpe = sql_query_array($this->_db, "
                SELECT DISTINCT
                    e.erkrankung_id,
                    e.datum
                FROM erkrankung erk
                    INNER JOIN eingriff_ops op ON op.erkrankung_id = erk.erkrankung_id AND LOCATE('5-604', op.prozedur) != 0
                    INNER JOIN eingriff e ON e.eingriff_id = op.eingriff_id
                WHERE
                    erk.erkrankung_id IN ({$this->getFilteredDiseases()}) AND erk.erkrankung = 'p'
            ");

            // rearrange for faster indexing
            $sort = array();

            foreach ($preRpe as $rpe) {
                $sort[$rpe['erkrankung_id']][] = $rpe['datum'];
            }

            $primaryRPEOps = $sort;
        }

        // check if diseaseId exists in primaryRPEOps
        if (array_key_exists($diseaseId, $primaryRPEOps) === true) {

            $earliestOpDate = min($primaryRPEOps[$diseaseId]);

            // if earliest op date lesser then situation start date
            if ($earliestOpDate < $startDate) {
                $previousRPE = '1';
            }
        }

        return $previousRPE;
    }


    /**
     * _buildPsaRPE
     *
     * @access  protected
     * @param   array $record
     * @return  string
     */
    protected function _buildPsaRPE(array $record)
    {
        $psaRpe = null;

        if (strlen($record['psa_rezidiv']) > 0 &&
            $record['durchgef_strahlenth'] == '1' &&
            $this->_buildPreviousRPE($record['erkrankung_id'], $record['sit_start_date']) == '1'
        ) {
            $psaRpe = '1';
        }

        return $psaRpe;
    }


    /**
     * _buildComplications
     *
     * @access  protected
     * @param   string  $complications
     * @return  string
     */
    protected function _buildComplications($complications)
    {
        $complicationValue = null;

        // only process if filled
        if (strlen($complications) > 0) {
            $complications = explode('|', $complications);

            $tmp = array();

            foreach ($complications as $complication) {
                $tmp[] = $this->_getCoding('komplikation', $complication);
            }

            $tmp = array_filter($tmp);

            // check tmp array
            if (count($tmp) > 0) {
                asort($tmp);

                $complicationValue = implode(', ', $tmp);
            }
        }

        return $complicationValue;
    }


    /**
     * _buildRevisionOpAfter90Days
     *
     * @access  protected
     * @param   array $record
     * @return  string
     */
    protected function _buildRevisionOpAfter90Days(array $record)
    {
        $revisionOp = null;

        if (strlen($record['revisions_op_90']) > 0 && strlen($record['bezug_rpe_kompl']) > 0) {

            $complications = $this->recordStringToArray($record['revisions_op_90'], array('datum','eingriff_id'));
            $rpe           = $this->recordStringToArray($record['bezug_rpe_kompl'], array('eingriff_id', 'datum'));

            foreach ($complications as $complication) {
                foreach ($rpe as $op) {
                    if ($op['eingriff_id'] === $complication['eingriff_id']) {
                        $diff = date_diff_days($op['datum'], $complication['datum']);

                        // check date difference
                        if ($diff >= 0 && $diff <= 89) {
                            $revisionOp = '1';

                            break 2;
                        }
                    }
                }
            }
        }

        return $revisionOp;
    }


    /**
     * _buildActiveSurveillanceAndWatchfulWaiting
     *
     * @access  protected
     * @param   $record
     * @return  array
     */
    protected function _buildActiveSurveillanceAndWatchfulWaiting(array $record)
    {
        $rowSeparator = reportExtensionP::SEPARATOR_ROWS;

        $result = array(
            'active_surveillance' => null,
            'watchful_waiting' => null
        );

        $dates = array();

        // iterate over record fields and explode therapyPlan dates for sorting
        foreach ($result as $fieldName => $dummy) {
            if (strlen($record[$fieldName]) > 0) {
                $therapyDates = explode($rowSeparator, $record[$fieldName]);

                foreach ($therapyDates as $date) {
                    $dates[$date] = $fieldName;
                }
            }
        }

        // if min one therapyPlan exist, fill result
        if (count($dates) > 0) {
            // sort asc
            ksort($dates);

            $earliestDate = reset(array_keys($dates));

            $choose = reset($dates);

            // set value to 1
            $result[$choose] = $earliestDate;
        }

        return $result;
    }


    /**
     * _buildOtherTherapies
     *
     * @access  protected
     * @param   string $therapies
     * @return  string
     */
    protected function _buildOtherTherapies($therapies)
    {
        $value = null;

        if (strlen($therapies) > 0) {
            $therapies = explode('|', $therapies);

            foreach ($therapies as &$therapy) {
                $therapy = $this->_getCoding('sonstige_art', $therapy);
            }

            $value = implode(', ', $therapies);
        }

        return $value;
    }


    /**
     * _buildReferenceData
     *
     * @access  protected
     * @param   array $record
     * @return  array
     */
    protected function _buildReferenceData(array $record)
    {
        // create empty data array
        $data = array_fill_keys(self::$referenceDateFields, null);

        // process dates only if primary case
        if ($this->_isPrimary($record) === true) {

            // known method calls for better parameter handling
            $data['bezug_rpe']  = $this->_getReferenceDateRpe($record);
            $data['bezug_rze']  = $this->_getReferenceDateRze($record);
            $data['bezug_seed'] = $this->_getReferenceDateSeed($record);
            $data['bezug_hdr']  = $this->_getReferenceDateHdr($record);
            $data['bezug_perk'] = $this->_getReferenceDatePerk($record);

            $asWw = $this->_buildActiveSurveillanceAndWatchfulWaiting($record);

            $data['bezug_as'] = $asWw['active_surveillance'];
            $data['bezug_ww'] = $asWw['watchful_waiting'];

            $data['bezugsdatum_cpz_andere_lokale_therapie']     = $this->_getReferenceDateCpzOtherLocalTherapy($record);
            $data['bezugsdatum_cpz_ausschliesslich_systemisch'] = $this->_getReferenceDateCpzSystemicTherapy($record);
            $data['bezugsdatum_cpz_andere_behandlung']          = $this->_getReferenceDateCpzOtherTreatment($record);

            $finalReferenceDates = array_filter($data);

            // process only if final reference
            if (count($finalReferenceDates) > 0) {
                $finalReferenceData = $this->_calculateFinalReferenceData($record, $finalReferenceDates);

                // append final reference data
                $data = array_merge($data, $finalReferenceData);
            }
        }

        return $data;
    }


    /**
     * _calculateFinalReferenceData
     * (attention: $dates must not contain null values and must not empty!)
     *
     * @access  public
     * @param   array $record
     * @param   array $dates
     * @return  array
     */
    protected function _calculateFinalReferenceData(array $record, array $dates)
    {
        $exclude = array(
            'bezug_as' => null,
            'bezug_ww' => null
        );

        $data = array(
            'therapie_bezugsdatum' => null,
            'bezugsdatum' => null
        );

        // sort dates and with storing key names
        asort($dates);

        // remove as and ww dates from date array so we could check prio 1 or prio 2
        $prio1Dates = array_diff_key($dates, $exclude);

        // check prio 1 dates (without as and ww!)
        if (count($prio1Dates) > 0) {
            $firstDate = reset($prio1Dates);
            $firstYear = substr($firstDate, 0, 4);

            // find first matching year
            foreach (self::$referenceDateFields as $fieldName) {
                if (array_key_exists($fieldName, $prio1Dates) === true) {

                    $fieldDate = $prio1Dates[$fieldName];

                    // if fieldDate year same as lowest year
                    if (substr($fieldDate, 0, 4) === $firstYear) {
                        $data['bezugsdatum'] = $fieldDate;
                        $data['therapie_bezugsdatum'] = $fieldName;

                        break;
                    }
                }
            }
        } else { // prio 2 this can only be one (as or ww)
            $data['bezugsdatum']          = reset($dates);
            $data['therapie_bezugsdatum'] = reset(array_keys($dates));
        }

        return $data;
    }


    /**
     * _getReferenceDateRpe
     * (returns the reference date for field "bezug_rpe")
     *
     * @access  protected
     * @param   array $record
     * @return  string
     */
    protected function _getReferenceDateRpe(array $record)
    {
        $date = null;

        // only process if field rpe is 1
        if ($record['rpe'] == '1') {
            // get first rpe form (it's an op form)
            $firstRecord = $this->_getFirstRecord($record['bezug_rpe'], array('id', 'date'), 'date');

            // if found (it should because rpe is 1) get reference date
            if ($firstRecord !== null) {
                // op contains "therapieplan_id" which is required for finding the reference date
                $op = $this->getOp($firstRecord['id']);

                $date = $this->_findReferenceDate($record['erkrankung_id'], $op['therapieplan_id'], $firstRecord['date']);
            }
        }

        return $date;
    }


    /**
     * _getReferenceDateRze
     * (returns the reference date for field "bezug_rze")
     *
     * @access  protected
     * @param   array $record
     * @return  string
     */
    protected function _getReferenceDateRze(array $record)
    {
        $date = null;

        if ($record['rze'] === '1') {
            // get first rze form (it's an op form)
            $firstRecord = $this->_getFirstRecord($record['bezug_rze'], array('id', 'date'), 'date');

            // if found (it should because rze is 1) get reference date
            if ($firstRecord !== null) {
                // op contains "therapieplan_id" which is required for finding the reference date
                $op = $this->getOp($firstRecord['id']);

                $incidentialFinding = strlen($record['zufallsbefund']) > 0;

                if ($incidentialFinding === true) {
                    $date = $firstRecord['date'];
                } else {
                    $date = $this->_findReferenceDate($record['erkrankung_id'], $op['therapieplan_id'], $firstRecord['date']);
                }
            }
        }

        return $date;
    }


    /**
     * _getReferenceDateSeed
     * (returns the reference date for field "bezug_seed")
     *
     * @access  protected
     * @param   array $record
     * @return  string
     */
    protected function _getReferenceDateSeed(array $record)
    {
        $date = null;

        if (strlen($record['str_permanent_seed']) > 0) {
            // get first seed form (it's an radio form)
            $firstRecord = $this->_getFirstRecord($record['bezug_seed'], array('id', 'date', 'therapyPlanId'), 'date');

            // if found (it should because str_permanent_seed is 1) get reference date
            if ($firstRecord !== null) {
                $date = $this->_findReferenceDate($record['erkrankung_id'], $firstRecord['therapyPlanId'], $firstRecord['date']);
            }
        }

        return $date;
    }


    /**
     * _getReferenceDateHdr
     * (returns the reference date for field "bezug_hdr")
     *
     * @access  public
     * @param   array   $record
     * @return  string
     */
    protected function _getReferenceDateHdr(array $record)
    {
        $date = null;

        if (strlen($record['hdr_brachytherapie']) > 0) {
            // get first hdr form (it's an radio form)
            $firstRecord = $this->_getFirstRecord($record['bezug_hdr'], array('id', 'date', 'therapyPlanId'), 'date');

            // if found (it should because hdr_brachytherapie is 1) get reference date
            if ($firstRecord !== null) {
                $date = $this->_findReferenceDate($record['erkrankung_id'], $firstRecord['therapyPlanId'], $firstRecord['date']);
            }
        }

        return $date;
    }


    /**
     * _getReferenceDatePerk
     * (returns the reference date for field "bezug_perk")
     *
     * @access  protected
     * @param   array   $record
     * @return  string
     */
    public function _getReferenceDatePerk(array $record)
    {
        $date = null;

        if (strlen($record['def_perkutane_strahlentherapie']) > 0) {
            // get first perk radio form (it's an radio form)
            $firstRecord = $this->_getFirstRecord($record['bezug_perk'], array('id', 'date', 'therapyPlanId'), 'date');

            // if found (it should because "def_perkutane_strahlentherapie" is 1) get reference date
            if ($firstRecord !== null) {
                $date = $this->_findReferenceDate($record['erkrankung_id'], $firstRecord['therapyPlanId'], $firstRecord['date']);
            }
        }

        return $date;
    }


    /**
     * _getReferenceDateOtherLocalTherapy
     *
     * @access  protected
     * @param   array   $record
     * @return  string
     */
    protected function _getReferenceDateCpzOtherLocalTherapy(array $record)
    {
        $date = null;

        if ($this->_cpz_andere_lokale_therapie($record) == '1') {
            // get cpz other local therapy form (it's an th_son form)
            $firstRecord = $this->_getFirstRecord($record['bezugsdatum_cpz_andere_lokale_therapie'], array('id', 'date', 'therapyPlanId'), 'date');

            // if found (it should because "_cpz_andere_lokale_therapie" is true) get reference date
            if ($firstRecord !== null) {
                $date = $this->_findReferenceDate($record['erkrankung_id'], $firstRecord['therapyPlanId'], $firstRecord['date']);
            }
        }

        return $date;
    }


    /**
     * returns the reference date for field "bezugsdatum_cpz_ausschliesslich_systemisch"
     *
     * @access  public
     * @param   array $record
     * @return  string
     */
    protected function _getReferenceDateCpzSystemicTherapy(array $record)
    {
        $date = null;

        if ($this->_cpz_ausschliesslich_systemisch($record) == '1') {
            // get cpz systemic therapy form (it's an th_sys form)
            $firstRecord = $this->_getFirstRecord($record['bezugsdatum_cpz_ausschliesslich_systemisch'], array('id', 'date', 'therapyPlanId'), 'date');

            // if found (it should because "_cpz_ausschliesslich_systemisch" is true) get reference date
            if ($firstRecord !== null) {
                $date = $this->_findReferenceDate($record['erkrankung_id'], $firstRecord['therapyPlanId'], $firstRecord['date']);
            }
        }

        return $date;
    }


    /**
     * _getReferenceDateCpzOtherTreatment
     *
     * @access  protected
     * @param   array $record
     * @return  string
     */
    protected function _getReferenceDateCpzOtherTreatment(array $record)
    {
        $date = null;

        if ($this->_cpz_andere_behandlung($record) == '1') {
            $stdFields = array('id', 'date', 'therapyPlanId');

            // build array of first therapy records
            $firstRecords = array(
                $this->_getFirstRecord($record['bezugsdatum_cpz_andere_behandlung_andere_behandlung'], $stdFields, 'date'),
                $this->_getFirstRecord($record['bezugsdatum_cpz_andere_behandlung_pall_strahlentherapie'], $stdFields, 'date'),
                $this->_getFirstRecord($record['bezugsdatum_cpz_andere_behandlung_therapie_systemisch'], $stdFields, 'date'),
                $this->_getFirstRecord($record['bezugsdatum_cpz_andere_behandlung_strahlentherapie'], $stdFields, 'date'),
                $this->_getFirstRecord($record['bezugsdatum_cpz_andere_behandlung_palliative_versorgung'], array('date', 'palli'), 'date')
            );

            // remove all null values (no therapy in this block)
            $firstRecords = array_filter($firstRecords);

            // process first records if min therapy or palli supply exists
            if (count($firstRecords) > 0) {
                $veryFirstRecords = array();

                // order very first records
                foreach ($firstRecords as $firstRecord) {
                    $veryFirstRecords[$firstRecord['date']][] = $firstRecord;
                }

                // order asc
                ksort($veryFirstRecords);

                // very first could also be more then 1 record (two therapies on same date)
                $veryFirstRecords = reset($veryFirstRecords);

                // only one very first therapy exists
                if (count($veryFirstRecords) === 1) {
                    $veryFirstRecord = reset($veryFirstRecords);

                    // if very first is palli supply
                    if (array_key_exists('palli', $veryFirstRecord) === true) {
                        $date = $veryFirstRecord['date'];
                    } else {
                        $date = $this->_findReferenceDate($record['erkrankung_id'], $veryFirstRecord['therapyPlanId'], $veryFirstRecord['date']);
                    }
                } else { // if two therapies on same date exists, get earliest therapyPlan date
                    $earliestTherapyPlanDates = array();

                    foreach ($veryFirstRecords as $firstRecord) {
                        // if very first is palli supply
                        if (array_key_exists('palli', $firstRecord) === true) {
                            $earliestTherapyPlanDates[] = $firstRecord['date'];
                        } else {
                            $earliestTherapyPlanDates[] = $this->_findReferenceDate($record['erkrankung_id'], $firstRecord['therapyPlanId'], $firstRecord['date']);
                        }
                    }

                    // remove null values
                    $earliestTherapyPlanDates = array_filter($earliestTherapyPlanDates);

                    // only take date if min one exists
                    if (count($earliestTherapyPlanDates) > 0) {
                        sort($earliestTherapyPlanDates);

                        $date = reset($earliestTherapyPlanDates);
                    }
                }
            }
        }

        return $date;
    }


    /**
     * _findReferenceDate
     *
     * @access  protected
     * @param   int    $diseaseId
     * @param   int    $therapyPlanId
     * @param   string $therapyDate
     * @return  string
     */
    protected function _findReferenceDate($diseaseId, $therapyPlanId, $therapyDate)
    {
        $date     = null;
        $timeline = array();

        // first get all therapyPlans for disease
        $therapyPlans = $this->_getTherapyPlans($diseaseId);

        // if therapyPlan was selected as relation in therapy, get tp date
        if ($therapyPlanId !== null) {
            foreach ($therapyPlans as $therapyPlan) {
                $tpDate = $therapyPlan['datum'];

                // find related therapyPlan
                if ($therapyPlan['id'] == $therapyPlanId) {
                    $date = $tpDate;
                    break;
                }
            }
        }

        // if date is null (cause $therapyPlanId is not set or no related therapyPlan found)
        if ($date === null) {
            $praeopConferences = $this->_getPraeopConferences($diseaseId);

            // find all praeop conference dates earlier then $therapyDate
            foreach ($praeopConferences as $conference) {
                $cDate = $conference['datum'];

                if ($cDate <= $therapyDate) {
                    $timeline[] = $cDate;
                }
            }

            // find all therapyPlan dates earlier then $therapyDate
            foreach ($therapyPlans as $therapyPlan) {
                $tpDate = $therapyPlan['datum'];

                if ($tpDate <= $therapyDate) {
                    $timeline[] = $tpDate;
                }
            }

            // only process if min one timeline entry exists
            if (count($timeline) > 0) {
                $timeline = array_unique($timeline);

                // sort timeline DESC
                rsort($timeline);

                // get nearest date to therapy
                $date = reset($timeline);
            }
        }

        return $date;
    }


    /**
     * _buildSwageFieldNames
     *
     * @access  protected
     * @param   array   $appending
     * @param   bool    $totalFields
     * @return  array
     */
    protected function _buildSwageFieldNames(array $appending = array(), $totalFields = false)
    {
        $fieldsNames = array();

        // totalFields   = r_beurteilung/r_laenge/r_tumoranteil
        foreach ($appending as $label) {
            if ($totalFields === true && in_array($label, array('beurteilung', 'laenge', 'tumoranteil'))) {
                $fieldsNames[] = 'r_' . $label;
                $fieldsNames[] = 'l_' . $label;
            }

            foreach ($this->_swageSections as $heads) {
                if (in_array($label, array('beurteilung', 'anz_positiv'))) {
                    $fieldsNames[] = $heads . '_' . $label;
                } else {
                    for ($i = 1; $i <= 5; $i++) {
                        $fieldsNames[] = $heads . '_' . $i. '_' . $label;
                    }
                }
            }
        }

        return $fieldsNames;
    }


    /**
     * _checkSwage
     *
     * @access  protected
     * @param   string $alias
     * @return  string
     */
    protected function _checkSwage($alias = 'h')
    {
        return "({$alias}." . implode(" IS NOT NULL OR {$alias}.", $this->_buildSwageFieldNames(array('laenge'))) . ' IS NOT NULL' . ')';
    }


    /**
     * _countSwage
     *
     * @access  protected
     * @param   string $alias
     * @return  string
     */
    protected function _countSwage($alias = 'h')
    {
        return "IF({$alias}." . implode(" >= 10,1,0) + IF({$alias}.", $this->_buildSwageFieldNames(array('laenge'))) . '>= 10,1,0)';
    }


    /**
     * _checkSwagePositive
     *
     * @access  protected
     * @param   string $alias
     * @return  string
     */
    protected function _checkSwagePositive($alias = 'h')
    {
        $fields = $this->_buildSwageFieldNames(array('beurteilung'), true);

        return "({$alias}." . implode(" IS NOT NULL OR {$alias}.", $fields) . ' IS NOT NULL' . ')';
    }


    /**
     * _countSwagePositive
     *
     * @access  protected
     * @param   string $alias
     * @return  string
     */
    protected function _countSwagePositive($alias = 'h')
    {
        $fields = $this->_buildSwageFieldNames(array('beurteilung'), true);

        $query = "
            IF({$alias}." . implode(" = 'p' OR {$alias}.", $fields) . "= 'p',
                1,
                IF({$alias}." . implode(" = 'n' OR {$alias}.", $fields) . "= 'n',
                   0,
                   NULL
                 )
            )
        ";

        return $query;
    }


    /**
     * _processSwages
     *
     * Daten können aus unterschiedlichen Histologieformularen stammen, müssen aber die gleich eingriff_id haben.
     *
     * Art ist "Befundung von Biopsie-Gewebe" UND Formular ist einem Eingriff mit mind. einem der folgenden OPS-Codes
     * zugeordnet:  1-464.0 bis 1-464.01
     *
     * in mindestens einem Abschnitt [SBR, SBL, BLR, BLL, BR, BL, TR, TL, MLR, MLL, MR, ML, AR, AL, ALR, ALL]
     * ist im Feld *_beurteilung "positiv" ausgewählt UND das zugehörige Feld *_anz_positiv ist gefüllt
     * UND
     * sind für mindestens eine Stanze (Nr. 1-5) jeweils alle Felder [*_laenge, *_tumoranteil, *_gleason1_anteil, *_gleason2_anteil, *_diff] befüllt
     * UND die Felder gleason1 UND gleason2 sind gefüllt
     *
     * @access  protected
     * @param   array $dataset
     * @return  string
     */
    protected function _buildSwageFindings(array $dataset)
    {
        $befundbericht_stanzen = $this->recordStringToArray($dataset['befundbericht_stanzen']);

        // nach eingriff_id sortieren
        $tmp = array();

        foreach ($befundbericht_stanzen as $pbs) {
            $tmp[$pbs[1]][] = $pbs;
        }

        // stanzen-query only if gleason 1 + 2 is filled in
        foreach ($tmp as $opId => $t) {
            if (count($this->_mergeForms($t)) === 4) {

                $swageFinding = $this->_getSwageFindingForOpId($opId);

                $merged_befundbericht_stanzen = $this->_mergeForms($swageFinding);

                foreach ($this->_swageSections as $section) {
                    if (array_key_exists($section . '_beurteilung', $merged_befundbericht_stanzen) && array_key_exists($section . '_anz_positiv', $merged_befundbericht_stanzen)) {
                        for ($i = 1; $i <= 5; $i ++) {
                            if (array_key_exists($section . '_' . $i . '_laenge', $merged_befundbericht_stanzen) &&
                                array_key_exists($section . '_' . $i . '_tumoranteil', $merged_befundbericht_stanzen) &&
                                array_key_exists($section . '_' . $i . '_gleason1_anteil', $merged_befundbericht_stanzen) &&
                                array_key_exists($section . '_' . $i . '_gleason2_anteil', $merged_befundbericht_stanzen) &&
                                array_key_exists($section . '_' . $i . '_diff', $merged_befundbericht_stanzen)
                            ) {
                                return '1';
                            }
                        }
                    }
                }
            }
        }

        return null;
    }


    /**
     * _getSwageFindingForOpId
     * (collect all histologies with filled swage fields)
     *
     * @access  protected
     * @param   int $opId
     * @return  array
     */
    protected function _getSwageFindingForOpId($opId)
    {
        $cache = $this->getCache('swage');
        $histoRecords = array();

        // build cache
        if ($cache === null) {
            $cache = array();
            $requiredFields = array();

            $parts = array('laenge', 'tumoranteil', 'gleason1_anteil', 'gleason2_anteil', 'diff');

            foreach ($this->_swageSections as $section) {
                $requiredFields[] = $section . '_beurteilung';
                $requiredFields[] = $section . '_anz_positiv';

                for ($i = 1; $i <= 5; $i++) {
                    foreach ($parts as $part) {
                        $requiredFields[] = $section . '_' . $i . '_' . $part;
                    }
                }
            }

            $requiredFields = implode(', ', $requiredFields);

            $filteredDiseases = $this->getFilteredDiseases();

            $records = sql_query_array($this->_db, "
                SELECT
                    h.erkrankung_id,
                    h.eingriff_id,
                    {$requiredFields}
                FROM histologie h
                    INNER JOIN eingriff e ON h.eingriff_id = e.eingriff_id
                WHERE
                    h.erkrankung_id IN ({$filteredDiseases})
            ");

            // map histo records to eingriff_id
            foreach ($records as $record) {
                $cache[$record['eingriff_id']][] = $record;
            }

            $this->setCache('swage', $cache);
        }

        if (array_key_exists($opId, $cache) === true) {
            $histoRecords = $cache[$opId];
        }

        return $histoRecords;
    }


    /**
     * _getPraeopConferences
     *
     * @access  protected
     * @param   int $diseaseId
     * @return  array
     */
    protected function _getPraeopConferences($diseaseId)
    {
        $cache = $this->getCache('praeopConferences');

        if ($cache === null) {
            $cache = $this->_mapArray(sql_query_array($this->_db, "
                SELECT
                    kp.konferenz_patient_id,
                    kp.erkrankung_id,
                    k.datum
                FROM konferenz_patient kp
                    INNER JOIN konferenz k ON k.konferenz_id = kp.konferenz_id
                WHERE
                    kp.art = 'prae' AND
                    kp.erkrankung_id IN ({$this->getFilteredDiseases()})
                GROUP BY
                    kp.konferenz_patient_id
            "), 'erkrankung_id', true);

            $this->setCache('praeopConferences', $cache);
        }

        $conferences = array();

        // find therapyPlans for diseaseId
        if (array_key_exists($diseaseId, $cache) === true) {
            $conferences = $cache[$diseaseId];
        }

        return $conferences;
    }


    /**
     * _getTherapyPlans
     *
     * @access  protected
     * @param   int $diseaseId
     * @return  array
     */
    protected function _getTherapyPlans($diseaseId)
    {
        $cache = $this->getCache('therapyPlans');

        if ($cache === null) {
            $orgId      = $this->getParam('org_id');
            $diseaseIds = $this->getFilteredDiseases();

            $cache = $this->_mapArray(sql_query_array($this->_db, "
                SELECT
                    tp.therapieplan_id as id,
                    tp.erkrankung_id,
                    tp.datum
                FROM therapieplan tp
                WHERE
                    tp.erkrankung_id IN ({$diseaseIds}) AND
                    (tp.org_id IS NULL OR tp.org_id = '{$orgId}')
            "), 'erkrankung_id', true);

            $this->setCache('therapyPlans', $cache);
        }

        $therapyPlans = array();

        // find therapyPlans for diseaseId
        if (array_key_exists($diseaseId, $cache) === true) {
            $therapyPlans = $cache[$diseaseId];
        }

        return $therapyPlans;
    }


    /**
     * getOp
     * (get op record for id. caching cause several fields in query want more data but joining only on status table)
     *
     * @access  protected
     * @param   int $opId
     * @return  array
     */
    protected function getOp($opId)
    {
        $record = null;
        $cache  = $this->getCache('op');

        // build cache if not already done
        if ($cache === null) {
            $diseaseIds = $this->getFilteredDiseases();

            $cache = $this->_mapArray(sql_query_array($this->_db, "
                SELECT
                    eingriff_id,
                    therapieplan_id
                FROM eingriff
                WHERE
                    erkrankung_id IN ({$diseaseIds})
            "), 'eingriff_id');

            $this->setCache('op', $cache);
        }

        // get record from cache with id
        if (array_key_exists($opId, $cache) === true) {
            $record = $cache[$opId];
        }

        return $record;
    }


    /**
     * combines multiple forms to one
     *
     * @access  protected
     * @param   array   $forms
     * @return  array
     */
    protected function _mergeForms(array $forms)
    {
        $merged_form = array();

        foreach ($forms as $preMerge) {
            foreach ($preMerge as $key => $field) {
                if (strlen($field) > 0 ) {
                    $merged_form[$key] = $field;
                }
            }
        }

        return $merged_form;
    }


    /**
     * _mapArray
     *
     * @access  protected
     * @param   array   $array
     * @param   string  $key
     * @param   bool $multidimensional
     * @return  array
     */
    protected function _mapArray(array $array, $key, $multidimensional = false)
    {
        $result = array();

        if ($multidimensional === false) {
            foreach ($array as $arr) {
                $result[$arr[$key]] = $arr;
            }
        } else {
            foreach ($array as $arr) {
                $result[$arr[$key]][] = $arr;
            }
        }

        return $result;
    }
}

?>
