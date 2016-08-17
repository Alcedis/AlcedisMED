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

    $having = "(diagnose LIKE 'C50%' OR diagnose IN ('D05.1','D05.7','D05.9')) AND
              ((RIGHT(morphologie,1) IN ('2', '3') AND morphologie != '8520/2') OR anlass LIKE 'r%') AND
       zweiterkrankung IS NULL
    ";

    $relevantSelectWhere = "ts.erkrankung_id = t.erkrankung_id AND ts.diagnose_seite IN ('B', t.diagnose_seite) AND ts.anlass = t.anlass";
    $relevantSelectOrder = "ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1";

    $rezidivOneStepCheck = $this->_rezidivOneStepCheck();

    $now = date('Y-m-d');

    $relevantSelects = array(
      "(
         SELECT
            IF(MAX(ts.nur_zweitmeinung) IS NOT NULL OR MAX(ts.nur_diagnosesicherung) IS NOT NULL OR MAX(ts.kein_fall) IS NOT NULL, 1, NULL)
         FROM tumorstatus ts
         WHERE
            {$relevantSelectWhere}
         LIMIT 1
      ) AS 'nicht_zaehlen'
      ",
      "(SELECT ts.tnm_praefix       FROM tumorstatus ts WHERE {$relevantSelectWhere} AND LEFT(ts.t, 1) = 'p' {$relevantSelectOrder})         AS pt_praefix",
      "(SELECT ts.m                 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND LEFT(ts.m, 1) = 'c' {$relevantSelectOrder})         AS cm",
      "(SELECT ts.t                 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND LEFT(ts.t, 1) = 'c' {$relevantSelectOrder})         AS ct",
      "(SELECT ts.n                 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND LEFT(ts.n, 1) = 'c' {$relevantSelectOrder})         AS cn",
      "(SELECT ts.m                 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND LEFT(ts.m, 1) = 'p' {$relevantSelectOrder})         AS pm",
      "(SELECT ts.diagnose_seite    FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.diagnose IS NOT NULL {$relevantSelectOrder})     AS diagnose_seite",
      "(SELECT ts.g                 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.g IS NOT NULL {$relevantSelectOrder})            AS g",
      "(SELECT ts.r_lokal           FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.r_lokal IS NOT NULL {$relevantSelectOrder})      AS r_lokal",
      "(SELECT ts.estro_urteil      FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.estro_urteil IS NOT NULL {$relevantSelectOrder}) AS estro_urteil",
      "(SELECT ts.prog_urteil       FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.prog_urteil IS NOT NULL {$relevantSelectOrder})  AS prog_urteil",
      "(SELECT ts.her2_urteil       FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.her2_urteil IS NOT NULL {$relevantSelectOrder})  AS her2_urteil",
      "(SELECT ts.lk_entf           FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.lk_entf IS NOT NULL {$relevantSelectOrder})      AS lk_entf",
      "(SELECT ts.lk_bef            FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.lk_bef IS NOT NULL {$relevantSelectOrder})       AS lk_bef",
      "(SELECT ts.resektionsrand    FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.resektionsrand IS NOT NULL {$relevantSelectOrder}) AS resektionsrand",

      "(SELECT ts.m                 FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.diagnose_seite IN ('B', t.diagnose_seite) AND ts.anlass = 'p' AND ts.m IS NOT NULL {$relevantSelectOrder}) AS primary_m",

      "(SELECT ts.datum_sicherung   FROM tumorstatus ts WHERE {$rezidivOneStepCheck} AND ts.rezidiv_lokal IS NOT NULL       ORDER BY ts.datum_sicherung ASC LIMIT 1) AS rezidiv_lokal_datum",
      "(SELECT ts.datum_sicherung   FROM tumorstatus ts WHERE {$rezidivOneStepCheck} AND ts.rezidiv_lk IS NOT NULL          ORDER BY ts.datum_sicherung ASC LIMIT 1) AS rezidiv_lk_datum",
      "(SELECT ts.datum_sicherung   FROM tumorstatus ts WHERE {$rezidivOneStepCheck} AND ts.rezidiv_metastasen IS NOT NULL  ORDER BY ts.datum_sicherung ASC LIMIT 1) AS rezidiv_metastasen_datum",
   );

   $preQuery = $this->_getPreQuery($having, array_merge($relevantSelects, $additionalTsSelects));

   $query = "
        SELECT
            {$additionalFields}
            sit.nachname                                                            AS 'nachname',
            sit.vorname                                                             AS 'vorname',
            sit.geburtsdatum                                                        AS 'geburtsdatum',
            sit.geschlecht                                                          AS 'geschlecht',
            sit.patient_nr                                                          AS 'patient_nr',
            IF(sit.anlass = 'p', 1, 0)                                              AS 'primaerfall',
            sit.anlass                                                              AS 'anlass_raw',
            {$this->_getAnlassCases()}                                              AS 'anlass_case',
            sit.datum_sicherung                                                     AS 'datum_sicherung',

            IF(
                COUNT(DISTINCT IF(a.entdeckung = 'sc', a.anamnese_id, null)) > 0,
                1,
                IF(
                    COUNT(DISTINCT IF(a.entdeckung IS NOT NULL AND a.entdeckung != 'sc', a.anamnese_id, null)) > 0,
                    0,
                    NULL
                )
            )                                                                       AS 'screening_patient',

            IF(
                sit.anlass LIKE 'r%' AND MIN(h.datum) IS NULL,
                IF(sit.start_date = '0000-00-00', sit.start_date_rezidiv, sit.start_date),
                MIN(h.datum)
            )                                                                       AS 'bezugsdatum',

            sit.diagnose_seite                                                      AS 'seite',
            sit.diagnose                                                            AS 'diagnose',

            IF(
                COUNT(DISTINCT IF(
                    th_sys.vorlage_therapie_art IN ('ah', 'ahst'),
                    th_sys.therapie_systemisch_id,
                    NULL
                )) OR
                COUNT(DISTINCT IF(
                    th_str.vorlage_therapie_art = 'ahst',
                    th_str.strahlentherapie_id,
                    NULL
                ))
                , 1, NULL
            )                                                                       AS 'durchgef_antih_th',

            IF(
                COUNT(DISTINCT IF(
                    th_sys.vorlage_therapie_art IN ('ah', 'ahst') AND th_sys.therapielinie = 'first',
                    th_sys.therapie_systemisch_id,
                    NULL
                )) > 0,
                1,
                IF(
                    COUNT(DISTINCT IF(
                        th_sys.vorlage_therapie_art IN ('ah', 'ahst') AND th_sys.therapielinie != 'first',
                        th_sys.therapie_systemisch_id,
                        NULL
                    )) > 0,
                    0,
                    NULL
                )
            )                                                                       AS 'therapielinie_anti_th',

            IF(
                COUNT(DISTINCT IF(
                    LEFT(th_sys.vorlage_therapie_art,1) = 'c',
                    th_sys.therapie_systemisch_id,
                    NULL
                )) OR
                COUNT(DISTINCT IF(
                    th_str.vorlage_therapie_art = 'cst',
                    th_str.strahlentherapie_id,
                    NULL
                ))
                , 1, NULL
            )                                                                       AS 'durchgef_chemoth',

            IF(
                COUNT(DISTINCT IF(
                    th_sys.vorlage_therapie_art IN ('ist', 'ci', 'i'),
                    th_sys.therapie_systemisch_id,
                    NULL
                ))
                OR
                COUNT(DISTINCT IF(
                    th_str.vorlage_therapie_art IN ('ist'),
                    th_str.strahlentherapie_id,
                    NULL
                ))
                , 1, NULL
            )                                                                       AS 'durchgef_immunth',

            IF(
                (
                    COUNT(DISTINCT IF(
                        th_sys.vorlage_therapie_id IN ({$this->_getPreSelect('trastuzumabImmun')}) AND DATEDIFF(th_sys.ende, th_sys.beginn) >= 365,
                        th_sys.therapie_systemisch_id,
                        NULL
                    )) OR
                    COUNT(DISTINCT IF(
                        th_str.vorlage_therapie_id IN ({$this->_getPreSelect('trastuzumabImmun')}) AND DATEDIFF(th_str.ende, th_str.beginn) >= 365,
                        th_str.strahlentherapie_id,
                        NULL
                    ))
                ) OR
                (
                    COUNT(DISTINCT IF(
                        th_sys.vorlage_therapie_id IN ({$this->_getPreSelect('trastuzumabImmun')}) AND th_sys.ende IS NULL AND th_sys.andauernd IS NOT NULL AND DATEDIFF('{$now}', th_sys.beginn) >= 365,
                        th_sys.therapie_systemisch_id,
                        NULL
                    )) OR
                    COUNT(DISTINCT IF(
                        th_str.vorlage_therapie_id IN ({$this->_getPreSelect('trastuzumabImmun')}) AND th_str.ende IS NULL AND th_str.andauernd IS NOT NULL AND DATEDIFF('{$now}', th_str.beginn) >= 365,
                        th_str.strahlentherapie_id,
                        NULL
                    ))
                )
                , 1, IF(
                    (
                        COUNT(DISTINCT IF(
                            th_sys.vorlage_therapie_id IN ({$this->_getPreSelect('trastuzumabImmun')}) AND DATEDIFF(th_sys.ende, th_sys.beginn) < 365,
                            th_sys.therapie_systemisch_id,
                            NULL
                        )) OR
                        COUNT(DISTINCT IF(
                            th_str.vorlage_therapie_id IN ({$this->_getPreSelect('trastuzumabImmun')}) AND DATEDIFF(th_str.ende, th_str.beginn) < 365,
                            th_str.strahlentherapie_id,
                            NULL
                        ))
                    ) OR
                    (
                        COUNT(DISTINCT IF(
                            th_sys.vorlage_therapie_id IN ({$this->_getPreSelect('trastuzumabImmun')}) AND th_sys.ende IS NULL AND th_sys.andauernd IS NOT NULL AND DATEDIFF('{$now}', th_sys.beginn) < 365,
                            th_sys.therapie_systemisch_id,
                            NULL
                        )) OR
                        COUNT(DISTINCT IF(
                            th_str.vorlage_therapie_id IN ({$this->_getPreSelect('trastuzumabImmun')}) AND th_str.ende IS NULL AND th_str.andauernd IS NOT NULL AND DATEDIFF('{$now}', th_str.beginn) < 365,
                            th_str.strahlentherapie_id,
                            NULL
                        ))
                    ),
                    0,
                    NULL
                )
            )                                                                       AS 'durchgef_immunth_trastuzumab',

            IF(
                COUNT(DISTINCT th_str.strahlentherapie_id)
                OR
                COUNT(DISTINCT IF(
                    th_sys.vorlage_therapie_art IN ('ahst', 'ist', 'cst', 'sonstr'),
                    th_sys.therapie_systemisch_id,
                    NULL
                ))
            , 1, NULL)                                                              AS 'durchgef_strahlenth',

            IF(
                COUNT(DISTINCT IF(
                    tp.ah = '1',
                    tp.therapieplan_id,
                    NULL
                )),
                1,
                IF(
                    MAX(tp.ah) = 0,
                    0,
                    NULL
                )
            )                                                                       AS 'gepl_antih_th',

            IF(
                COUNT(DISTINCT IF(
                    tp.ah = '1' AND
                    tp.ah_intention IN ('kurna', 'palna'),
                    tp.therapieplan_id,
                    NULL
                )),
                1,
                IF(MAX(tp.ah) = 0, 0, NULL)
            )                                                                       AS 'gepl_neoadj_antih_th',

            IF(
                COUNT(DISTINCT IF(
                    tp.ah = '1' AND
                    tp.ah_intention IN ('kura', 'pala'),
                    tp.therapieplan_id,
                    NULL
                )),
                1,
                IF(
                    MAX(tp.ah) = 0,
                    0,
                    NULL
                )
            )                                                                       AS 'gepl_adj_antih_th',

            IF(
                COUNT(DISTINCT IF(
                    tp.chemo = '1' AND
                    tp.chemo_intention IN ('kurna', 'palna'),
                    tp.therapieplan_id,
                    NULL
                )),
                1,
                IF(
                    MAX(tp.chemo) = 0,
                    0,
                    NULL
                )
            )                                                                       AS 'gepl_neoadj_chemoth',
            IF(
                COUNT(DISTINCT IF(
                    tp.chemo = '1' AND
                    tp.chemo_intention IN ('kura', 'pala'),
                    tp.therapieplan_id,
                    NULL
                )),
                1,
                IF(
                    MAX(tp.chemo) = 0,
                    0,
                    NULL
                )
            )                                                                       AS 'gepl_adj_chemoth',
            IF(
                COUNT(DISTINCT IF(
                    tp.immun = '1',
                    tp.therapieplan_id,
                    NULL
                )),
                1,
                IF(
                    MAX(tp.immun) = 0,
                    0,
                    NULL
                )
            )                                                                       AS 'gepl_immun_th',

            IF(
                COUNT(DISTINCT IF(
                    tp.immun = '1' AND tp.immun_id IN ({$this->_getPreSelect('trastuzumabImmun')}),
                    tp.therapieplan_id,
                    NULL
                )),
                1,
                IF(COUNT(DISTINCT IF(tp.immun = '1', tp.therapieplan_id, NULL)) OR MAX(tp.immun) = 0,
                    0,
                    NULL
                )
            )                                                                       AS 'gepl_immun_th_trastuzumab',

            IF(
                COUNT(DISTINCT IF(
                    tp.immun = '1' AND
                    tp.immun_intention IN ('kurna', 'palna'),
                    tp.therapieplan_id,
                    NULL
                )),
                1,
                IF(
                    MAX(tp.immun) = 0,
                    0,
                    NULL
                )
            )                                                                       AS 'gepl_neoadj_immunth',

            IF(
                COUNT(DISTINCT IF(
                    tp.immun = '1' AND
                    tp.immun_intention IN ('kura', 'pala'),
                    tp.therapieplan_id,
                    NULL
                )),
                1,
                IF(
                    MAX(tp.immun) = 0,
                    0,
                    NULL
                )
            )                                                                       AS 'gepl_adj_immunth',

            IF(
                COUNT(DISTINCT IF(
                    tp.strahlen = '1' AND
                    tp.strahlen_intention IN ('kura', 'pala'),
                    tp.therapieplan_id,
                    NULL
                )) > 0,
                1,
                IF(
                    MAX(tp.strahlen) = 0,
                    0,
                    NULL
                )
            )                                                                       AS 'gepl_adj_strahlenth',

            IF(
                COUNT(DISTINCT IF(
                    th_str.intention IN ('kura', 'pala'),
                    th_str.strahlentherapie_id,
                    NULL
                )), 1, NULL
            )                                                                       AS 'durchgef_adj_strahlenth',

            IF(
                COUNT(DISTINCT IF(th_str.intention IN ('kurna', 'palna'), 1, NULL)) OR
                COUNT(DISTINCT IF(th_sys.intention IN ('kurna', 'palna'), 1, NULL)) OR
                COUNT(DISTINCT IF(th_son.intention IN ('kurna', 'palna'), 1, NULL)),
                1,
                NULL
            )                                                                                      AS 'durchgef_neoadj_therapie',

            sit.morphologie                                                                        AS 'icd_o_3',

            MIN(IF(h.art = 'pr', h.datum, NULL))                                                   AS 'datumpraeop_hist',

            IF(
                COUNT(DISTINCT IF(
                    s.form = 'eingriff' AND SUBSTRING(s.report_param, 3, 1) IN ('B', sit.diagnose_seite),
                        (SELECT 1 FROM eingriff_ops eo
                        WHERE
                            eo.eingriff_id = s.form_id AND eo.prozedur_seite IN ('B', sit.diagnose_seite) AND (
                            LOCATE('1-e03.', eo.prozedur) != 0 OR
                            LOCATE('1-e02.', eo.prozedur) != 0 OR
                            LOCATE('1-494.31', eo.prozedur) != 0 OR
                            LOCATE('1-494.32', eo.prozedur) != 0 OR
                            LOCATE('1-493.31', eo.prozedur) != 0 OR
                            LOCATE('1-493.32', eo.prozedur) != 0
                            )
                        GROUP BY
                            eo.eingriff_id
                    ),
                    NULL
                )) OR
                COUNT(DISTINCT IF(
                    LOCATE('1-e03.', u.art) != 0 OR
                    LOCATE('1-e02.', u.art) != 0 OR
                    LOCATE('1-494.31', u.art) != 0 OR
                    LOCATE('1-494.32', u.art) != 0 OR
                    LOCATE('1-493.31', u.art) != 0 OR
                    LOCATE('1-493.32', u.art) != 0,
                    u.untersuchung_id,
                    NULL
                ))
                , 1, NULL
            )                                                                                      AS 'stanz_vaku',

            MAX(IF(1 IN (op.art_primaertumor, op.art_rezidiv), op.datum, NULL))                    AS 'datumprimaer_op',

            MAX(IF(1 IN (op.art_primaertumor, op.art_rezidiv),
               (SELECT l.bez FROM l_basic l WHERE l.klasse = 'praeop_mark' AND l.code = op.mark),
               NULL
            ))                                                                                     AS 'praeop_mark',

            MAX(IF(1 IN (op.art_primaertumor, op.art_rezidiv) AND op.mark = '1d',
                IF(1 IN (op.mark_mammo, op.mark_sono), 1, 0),
                NULL
            ))                                                                                     AS 'drahtmarkierung_ges',

            MAX(IF(1 IN (op.art_primaertumor, op.art_rezidiv),
               (SELECT l.bez FROM l_basic l WHERE l.klasse = 'mark_abstand' AND l.code = op.mark_abstand),
               NULL
            ))                                                                                     AS 'drahtmarkierung',

            MAX(IF(1 IN (op.art_primaertumor, op.art_rezidiv), op.mark_mammo, NULL))               AS 'praeop_mammographie',
            MAX(IF(1 IN (op.art_primaertumor, op.art_rezidiv), op.intraop_roe, NULL))              AS 'intraop_roentgen',
            MAX(IF(1 IN (op.art_primaertumor, op.art_rezidiv), op.intraop_sono, NULL))             AS 'intraop_sono',
            MAX(IF(1 IN (op.art_primaertumor, op.art_rezidiv), op.schnellschnitt, NULL))           AS 'intraop_schnellschnitt',

            MAX(IF(
               1 IN (op.art_primaertumor, op.art_rezidiv) AND op.operateur1_id IS NOT NULL,
               (SELECT CONCAT_WS(', ', u.nachname, u.vorname) FROM user u WHERE u.user_id = op.operateur1_id LIMIT 1),
               NULL
            ))                                                                                     AS 'operateur',

            MAX(IF(
               1 IN (op.art_primaertumor, op.art_rezidiv) AND op.operateur2_id IS NOT NULL,
               (SELECT CONCAT_WS(', ', u.nachname, u.vorname) FROM user u WHERE u.user_id = op.operateur2_id LIMIT 1),
               NULL
            ))                                                                                     AS 'zweit_operateur',

            IF(
                COUNT(DISTINCT IF(
                    s.form = 'eingriff' AND SUBSTRING(s.report_param, 3, 1) IN ('B', sit.diagnose_seite),
                    (SELECT 1 FROM eingriff_ops eo
                        WHERE
                            eo.eingriff_id = s.form_id AND eo.prozedur_seite IN ('B', sit.diagnose_seite) AND
                            (LOCATE('5-872', eo.prozedur) != 0 OR
                            LOCATE('5-873', eo.prozedur) != 0 OR
                            LOCATE('5-874', eo.prozedur) != 0 OR
                            LOCATE('5-875', eo.prozedur) != 0 OR
                            LOCATE('5-876', eo.prozedur) != 0 OR
                            LOCATE('5-877', eo.prozedur) != 0)
                        GROUP BY
                            eo.eingriff_id
                    ),
                    NULL
                )), 1, NULL
            )                                                                 AS 'mastektomie',

            IF(
                COUNT(DISTINCT IF(
                    s.form = 'eingriff' AND LEFT(s.report_param, 1) = 1 AND SUBSTRING(s.report_param, 3, 1) IN ('B', sit.diagnose_seite),
                    (SELECT 1 FROM eingriff_ops eo
                        WHERE
                            eo.eingriff_id = s.form_id AND eo.prozedur_seite IN ('B', sit.diagnose_seite) AND
                            (LOCATE('5-872', eo.prozedur) != 0 OR
                            LOCATE('5-873', eo.prozedur) != 0 OR
                            LOCATE('5-874', eo.prozedur) != 0 OR
                            LOCATE('5-875', eo.prozedur) != 0 OR
                            LOCATE('5-876', eo.prozedur) != 0 OR
                            LOCATE('5-877', eo.prozedur) != 0)
                        GROUP BY
                            eo.eingriff_id
                    ),
                    NULL
                )), 1, NULL
            )                                                                 AS 'mastektomiebeiprimaer_op',

            GROUP_CONCAT(DISTINCT IF(
                s.form = 'eingriff' AND LEFT(s.report_param, 1) = 1 AND SUBSTRING(s.report_param, 3, 1) IN ('B', sit.diagnose_seite),
                (SELECT
                     GROUP_CONCAT(DISTINCT eo.prozedur SEPARATOR ' ')
                 FROM eingriff_ops eo
                 WHERE
                     eo.eingriff_id = s.form_id AND eo.prozedur_seite IN ('B', sit.diagnose_seite)
                 GROUP BY
                     eo.eingriff_id
                ),
                NULL
                )
            )                                                                 AS 'opsprimaer_op',

            IF(
                COUNT(DISTINCT IF(
                    s.form = 'eingriff' AND SUBSTRING(s.report_param, 3, 1) IN ('B', sit.diagnose_seite),
                    (SELECT 1 FROM eingriff_ops eo
                        WHERE
                            eo.eingriff_id = s.form_id AND eo.prozedur_seite IN ('B', sit.diagnose_seite) AND
                            (LOCATE('5-870', eo.prozedur) != 0 OR
                             LOCATE('5-871', eo.prozedur) != 0)
                        GROUP BY
                            eo.eingriff_id
                    ),
                    NULL
                )), 1, NULL
            )                                                                 AS 'bet',

            COUNT(
                DISTINCT IF(
                    '1' IN (op.art_primaertumor, op.art_lk,
                        op.art_metastasen, op.art_rezidiv,
                        op.art_nachresektion, op.art_revision) OR op.art_sonstige IS NOT NULL,
                    op.eingriff_id,
                    NULL
                )
            )                                                                 AS 'anz_ops',

            IF(
                COUNT(DISTINCT IF(
                     s.form = 'eingriff' AND SUBSTRING(s.report_param, 3, 1) IN ('B', sit.diagnose_seite),
                     (SELECT 1 FROM eingriff_ops eo
                        WHERE
                            eo.eingriff_id = s.form_id AND eo.prozedur_seite IN ('B', sit.diagnose_seite) AND
                            (LOCATE('5-883',   eo.prozedur) != 0 OR
                             LOCATE('5-885',   eo.prozedur) != 0 OR
                             LOCATE('5-886',   eo.prozedur) != 0 OR
                             LOCATE('5-889.2', eo.prozedur) != 0 OR
                             LOCATE('5-889.3', eo.prozedur) != 0 OR
                             LOCATE('5-889.4', eo.prozedur) != 0 OR
                             LOCATE('5-889.5', eo.prozedur) != 0)
                        GROUP BY
                            eo.eingriff_id
                    ),
                    NULL
                )), 1, NULL
            )                                                                 AS 'rekonstruktion',

            GROUP_CONCAT(
               DISTINCT
                  IF(
                     op.sln_markierung IS NOT NULL,
                     (SELECT l.bez FROM l_basic l WHERE l.klasse = 'sln_markierung' AND l.code = op.sln_markierung LIMIT 1),
                     NULL
                  )
               SEPARATOR ', '
            )                                                                 AS 'op_art_markierung',

            MAX(op.art_nachresektion)                                         AS 'nachresektion',

            IF(
                COUNT(DISTINCT IF(
                    s.form = 'eingriff' AND SUBSTRING(s.report_param, 3, 1) IN ('B', sit.diagnose_seite),
                    (SELECT 1 FROM eingriff_ops eo
                        WHERE
                            eo.eingriff_id = s.form_id AND eo.prozedur_seite IN ('B', sit.diagnose_seite) AND
                            (LOCATE('5-402.1', eo.prozedur) != 0 OR
                            LOCATE('5-404.0',  eo.prozedur) != 0 OR
                            LOCATE('5-406.1',  eo.prozedur) != 0 OR
                            LOCATE('5-407.02',  eo.prozedur) != 0 OR
                            LOCATE('5-871',    eo.prozedur) != 0 OR
                            LOCATE('5-873',    eo.prozedur) != 0 OR
                            LOCATE('5-875.0',  eo.prozedur) != 0 OR
                            LOCATE('5-875.1',  eo.prozedur) != 0 OR
                            LOCATE('5-875.2',  eo.prozedur) != 0 )
                        GROUP BY
                            eo.eingriff_id
                    ),
                    NULL
                )), 1, NULL
            )                                                                 AS 'axilla_diss',

            IF(
                COUNT(DISTINCT IF(
                    s.form = 'eingriff' AND SUBSTRING(s.report_param, 3, 1) IN ('B', sit.diagnose_seite),
                    (SELECT 1 FROM eingriff_ops eo
                        WHERE
                            eo.eingriff_id = s.form_id AND eo.prozedur_seite IN ('B', sit.diagnose_seite) AND
                            (LOCATE('5-401.11', eo.prozedur) != 0 OR
                            LOCATE('5-401.12',  eo.prozedur) != 0 OR
                            LOCATE('5-401.13',  eo.prozedur) != 0 OR
                            LOCATE('5-e21.y',  eo.prozedur) != 0)
                        GROUP BY
                            eo.eingriff_id
                    ),
                    NULL
                )), 1, NULL
            )                                                                 AS 'sln_biopsie',

            IF(SUM(op.sln_anzahl) > 0, 1,
               IF(SUM(op.sln_anzahl) = 0, 0, NULL)
            )                                                                 AS 'slndetektiert',

            SUM(h.lk_sentinel_entf)                                           AS 'slnentfernt',
            SUM(h.lk_sentinel_bef)                                            AS 'slnbefallen',
            sit.ct                                                            AS 'ct',
            sit.cn                                                            AS 'cn',
            sit.pt_praefix                                                    AS 'pt_praefix',
            sit.pt                                                            AS 'pt',
            IF(RIGHT(sit.pn, 4) != '(sn)', sit.pn, '')                        AS 'pn',
            IF(
                LEFT(sit.pn,1) = 'p' AND RIGHT(sit.pn, 4) = '(sn)',
                sit.pn,
                NULL
            )                                                                 AS 'pn_sn',
            IFNULL(sit.pm, sit.cm)                                            AS 'm',
            sit.g                                                             AS 'g',
            sit.r                                                             AS 'r',
            sit.r_lokal                                                       AS 'r_lokal',
            sit.resektionsrand                                                AS 'sicherheitsabstand',
            IF(
                'p' IN (sit.estro_urteil, sit.prog_urteil),
                'p',
                IF(
                    'n' IN (sit.estro_urteil, sit.prog_urteil),
                    'n',
                    NULL
                )
            )                                                                 AS 'rezeptorbefund',

            IF(
                'p' IN (sit.her2_urteil),
                'p',
                IF(
                    'n' IN (sit.her2_urteil),
                    'n',
                    NULL
                )
            )                                                                 AS 'her_2neu',
            sit.lk_entf                                                       AS 'lkentfernt',
            sit.lk_bef                                                        AS 'lkbefallen',

            GROUP_CONCAT(DISTINCT IF(k.eingriff_id IS NOT NULL AND k.revisionsoperation IS NOT NULL,
                CONCAT_WS('|', k.eingriff_id, k.revisionsoperation),
                NULL
            ))                                                                     AS 'revisions_kompl',

            GROUP_CONCAT(DISTINCT IF(op.eingriff_id IS NOT NULL, op.eingriff_id, NULL)) AS 'eingriff_ids',

            COUNT(DISTINCT IF(op.art_revision = '1', op.eingriff_id, NULL)) > 0   AS 'revisions_op',

            GROUP_CONCAT(DISTINCT k.komplikation SEPARATOR '|')                     AS komplikation,

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
            )                                                                AS 'praeop_tumorkonf',

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
            )                                                                 AS 'postop_tumorkonf',

            MAX(tp.studie)                                                    AS 'studienteilnahmegeplant',

            GROUP_CONCAT(DISTINCT
               IF(s.form = 'studie',
                   CONCAT_WS('|', s.form_id, DATE_FORMAT(s.report_param, '%d.%m.%Y')),
                   NULL
               )
               SEPARATOR ', '
            )                                                                 AS 'datum_studie',

            COUNT(DISTINCT IF(s.form = 'studie', s.form_id, NULL))            AS 'count_studie',

            GROUP_CONCAT(DISTINCT
               IF(b.psychoonkologie = 1,
                   CONCAT_WS(',', b.datum, b.psychoonkologie_dauer),
                   NULL
               )
               SEPARATOR '|'
            )                                                                 AS 'psychoonk_betreuung',

            MAX(DISTINCT b.sozialdienst)                                      AS 'betreuungsozialdienst',

            IF(COUNT(IF(s.form = 'fragebogen' AND s.report_param IN ({$this->_getPreSelect('zufrFragebogen')}), s.form_id, NULL)),1, NULL) AS 'befragungsbogen',

            MAX(n.datum)                                                      AS 'letztenachsorge',

            IF(sit.rezidiv_lokal_datum IS NOT NULL AND sit.rezidiv_lk_datum IS NOT NULL,
               IF(
                  sit.rezidiv_lokal_datum < sit.rezidiv_lk_datum,
                  sit.rezidiv_lokal_datum,
                  sit.rezidiv_lk_datum
               ),
               IFNULL(sit.rezidiv_lokal_datum, sit.rezidiv_lk_datum)
            )                                                                 AS 'datumlokalrezidiv',

            sit.rezidiv_metastasen_datum                                      AS 'datumfernmetastase',

            IF(COUNT(IF(s.form = 'ekr', s.status_id,NULL)) > 0,
               1,
               NULL
            )                                                                 AS 'meldung_kr',

            IF(
                MAX(x.abschluss_grund) IS NOT NULL AND MAX(x.abschluss_grund) = 'lost',
                1,
                IF(
                   MAX(x.abschluss_grund) IS NOT NULL AND MAX(x.abschluss_grund) != 'lost',
                   0,
                   NULL
                )
            )                                                                 AS 'losttofu',

            MAX(x.todesdatum)                                                 AS 'todesdatum',

            IF(
                MAX(x.tod_tumorassoziation) IS NOT NULL AND MAX(x.tod_tumorassoziation) IN ('tott', 'totn'),
                1,
                IF(
                   MAX(x.tod_tumorassoziation) IS NOT NULL AND MAX(x.tod_tumorassoziation) NOT IN ('tott', 'totn'),
                   0,
                   NULL
                )
            )                                                                 AS 'todtumorbedingt',

            sit.primary_m                                                     AS 'primary_m',

            sit.erkrankung_id                                                 AS 'erkrankungId',
            sit.patient_id                                                    AS 'patient_id'

        FROM ({$preQuery}) sit
            {$this->_innerStatus()}
            {$this->_statusJoin('anamnese a')}

            LEFT JOIN histologie h   ON s.form = 'histologie'   AND h.histologie_id    = s.form_id AND h.diagnose_seite IN ('B', sit.diagnose_seite)
            LEFT JOIN untersuchung u ON s.form = 'untersuchung' AND u.untersuchung_id  = s.form_id AND u.art_seite IN ('B', sit.diagnose_seite)

            {$this->_statusJoin('therapie_systemisch th_sys')}
            {$this->_statusJoin('strahlentherapie th_str')}
            {$this->_statusJoin('sonstige_therapie th_son')}
            {$this->_statusJoin('therapieplan tp')}

            LEFT JOIN eingriff op ON s.form = 'eingriff' AND op.eingriff_id = s.form_id AND op.diagnose_seite IN ('B', sit.diagnose_seite)

            {$this->_statusJoin('komplikation k')}
            {$this->_statusJoin('beratung b')}

            LEFT JOIN nachsorge n                  ON s.form = 'nachsorge' AND LOCATE(CONCAT_WS('','-',sit.erkrankung_id,'-'), s.report_param) > 0 AND
                                                      n.nachsorge_id = s.form_id
            {$this->_statusJoin('abschluss x')}

            {$additionalJoins}
        WHERE
            {$this->_getNcState()}
        GROUP BY
            sit.patient_id,
            sit.erkrankung_id,
            sit.anlass,
            sit.diagnose_seite
        HAVING
            {$this->_buildHaving()}
            {$additionalCondition}
        ORDER BY
            nachname, vorname, patient_id, erkrankungId, bezugsdatum
    ";

    $data = sql_query_array($this->_db, $query);

    $lKomplikation = getLookup($this->_db, 'komplikation');

    foreach ($data as $i => $dataset) {
        if ($dataset['revisions_op'] != 1) {
            $complications = array();
            $ops = array();
            $state = NULL;

            if (strlen($dataset['revisions_kompl']) > 0) {
                foreach (explode(',', $dataset['revisions_kompl']) as $tmpData) {
                    $tmpExplode = explode('|', $tmpData);
                    $complications[] = array('eingriff_id' => $tmpExplode[0], 'revisionsoperation' => $tmpExplode[1]);
                }
            }

            if (strlen($dataset['eingriff_ids']) > 0) {
                $ops = explode(',', $dataset['eingriff_ids']);
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

       $data[$i]['bet']                 = $dataset['bet'] == '1' && $dataset['mastektomie'] == '1' ? null : $dataset['bet'];
       $data[$i]['datum_studie']        = $this->_removeIdentifier($dataset['datum_studie']);
       $data[$i]['psychoonk_betreuung'] = $this->_selectMaxByDate($dataset['psychoonk_betreuung']);

       //Komplikation
       $komplikationen = strlen($dataset['komplikation']) > 0 ? explode('|', $dataset['komplikation']) : null;

       if ($komplikationen !== null) {
           $tmp = array();

           foreach($komplikationen as $komplikation) {
               if (isset($lKomplikation[$komplikation]) === true) {
                    $tmp[] = $lKomplikation[$komplikation];
                }
           }

           asort($tmp);

           $data[$i]['komplikation'] = implode(', ', $tmp);
       }
    }
?>
