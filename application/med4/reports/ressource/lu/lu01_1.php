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

    $orgId = $this->getParam('org_id');

   $relevantSelectWhere = "ts.erkrankung_id = t.erkrankung_id AND ts.diagnose_seite IN ('B', t.diagnose_seite) AND ts.anlass = t.anlass";
   $relevantSelectOrder = "ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1";

   $rezidivOneStepCheck = $this->_rezidivOneStepCheck();

   $stageCalc = stageCalc::create($this->_db, $this->_params['sub']);

   $relevantSelects = array(
      $stageCalc->select('c', 'uicc', true)  . "AS 'uicc_prae'",
      $stageCalc->select(null, 'uicc', true)     . "AS 'uicc'",
      "(
         SELECT
            IF(MAX(ts.nur_zweitmeinung) IS NOT NULL OR MAX(ts.nur_diagnosesicherung) IS NOT NULL OR MAX(ts.kein_fall) IS NOT NULL, 1, NULL)
         FROM tumorstatus ts
         WHERE
            {$relevantSelectWhere}
      ) AS 'nicht_zaehlen'
      ",
      "(SELECT ts.diagnose_seite    FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.diagnose IS NOT NULL {$relevantSelectOrder}) AS diagnose_seite",
      "(SELECT ts.t                 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND LEFT(ts.t, 1) = 'c' {$relevantSelectOrder}) AS ct",
      "(SELECT ts.n                 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND LEFT(ts.n, 1) = 'c' {$relevantSelectOrder}) AS cn",
      "(SELECT ts.g                 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.g IS NOT NULL {$relevantSelectOrder}) AS g",
      "(SELECT ts.m                 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.m IS NOT NULL {$relevantSelectOrder}) AS m",
      "(SELECT ts.t                 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.t IS NOT NULL {$relevantSelectOrder}) AS t",
      "(SELECT ts.n                 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.n IS NOT NULL {$relevantSelectOrder}) AS n",

      "(SELECT ts.r_lokal           FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.r_lokal IS NOT NULL {$relevantSelectOrder}) AS r_lokal",
      "(SELECT ts.lk_entf           FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.lk_entf IS NOT NULL {$relevantSelectOrder}) AS lk_entf",
      "(SELECT ts.lk_bef            FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.lk_bef IS NOT NULL {$relevantSelectOrder}) AS lk_bef",
      "(SELECT ts.resektionsrand    FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.resektionsrand IS NOT NULL {$relevantSelectOrder}) AS resektionsrand",

      "(SELECT ts.datum_sicherung   FROM tumorstatus ts WHERE {$rezidivOneStepCheck} AND ts.rezidiv_lokal IS NOT NULL       AND ts.diagnose_seite IN ('B', t.diagnose_seite) ORDER BY ts.datum_sicherung ASC LIMIT 1) AS rezidiv_lokal_datum",
      "(SELECT ts.datum_sicherung   FROM tumorstatus ts WHERE {$rezidivOneStepCheck} AND ts.rezidiv_lk IS NOT NULL          AND ts.diagnose_seite IN ('B', t.diagnose_seite) ORDER BY ts.datum_sicherung ASC LIMIT 1) AS rezidiv_lk_datum",
      "(SELECT ts.datum_sicherung   FROM tumorstatus ts WHERE {$rezidivOneStepCheck} AND ts.rezidiv_metastasen IS NOT NULL  AND ts.diagnose_seite IN ('B', t.diagnose_seite) ORDER BY ts.datum_sicherung ASC LIMIT 1) AS rezidiv_metastasen_datum"
    );

    $preQuery = $this->_getPreQuery('diagnose LIKE "C34%"', array_merge($relevantSelects, $additionalTsSelects));

    $primaryCases = $this->_detectPrimaryCases($preQuery, $stageCalc);

    //Cisplatin vorlagen
    $cisplatinVorlagen = dlookup($this->_db, "vorlage_therapie vt INNER JOIN vorlage_therapie_wirkstoff vtw ON vtw.vorlage_therapie_id = vt.vorlage_therapie_id AND vtw.wirkstoff = 'cisplatin'", "GROUP_CONCAT(DISTINCT vt.vorlage_therapie_id)", "LOCATE('c', vt.art) != 0");
    $cisplatinVorlagen = strlen($cisplatinVorlagen) > 0 ? explode(',', $cisplatinVorlagen) : array();


    $bronchoCodes   = '5-324.22|5-324.23|5-324.32|5-324.33|5-324.34|5-324.62|5-324.a2|5-324.a3|5-324.a4|5-324.b2|5-324.b3|5-324.b4|5-324.x2|5-324.x3|5-324.x4|5-325.1|5-325.2|5-325.3|5-325.6|5-325.7|5-325.8';

    $lungenresektion = $this->_getLungenresektionsCodes();
    $pneumektomie    = $this->_eingriffCase(explode('|', '5-327|5-328'), 'eo.prozedur');
    $broncho         = $this->_eingriffCase(explode('|', $bronchoCodes), 'eo.prozedur');
    $anastomose      = $this->_eingriffCase(explode('|', '5-321.1'), 'eo.prozedur');

    $query = "
      SELECT
        {$additionalFields}
        sit.nachname                                                       AS nachname,
        sit.vorname                                                        AS vorname,
        sit.geburtsdatum                                                   AS geburtsdatum,
        sit.patient_nr                                                     AS patient_nr,

        IF(sit.morphologie IS NOT NULL,
            IF(sit.morphologie LIKE '824%' OR LEFT(sit.morphologie, 4) IN('8041', '8042', '8043', '8044', '8045'), 0, 1),
            NULL
        )                                                                  AS nsclc_patient,

        null                                                               AS 'primaerfall',

        {$this->_getAnlassCases()}                                         AS 'anlass_case',

        IF(
            sit.anlass LIKE 'r%' AND MIN(h.datum) IS NULL,
            IF(sit.start_date = '0000-00-00', sit.start_date_rezidiv, sit.start_date),
            MIN(h.datum)
        )                                                                  AS bezugsdatum,

        sit.diagnose                                                       AS diagnose,

        sit.diagnose_seite                                                 AS seite,

        GROUP_CONCAT(DISTINCT IF(a.ecog IS NOT NULL, CONCAT_WS(',', a.datum, a.ecog), NULL) SEPARATOR '|') AS ecog,

        IF(
            COUNT(DISTINCT IF(tp.intention = 'kur' AND tp.org_id='{$orgId}', tp.therapieplan_id, NULL)) > 0 OR
            COUNT(DISTINCT IF(op.art_primaertumor IS NOT NULL AND op.org_id='{$orgId}', op.eingriff_id, NULL)) > 0 OR
            COUNT(DISTINCT IF(th_sys.intention IN ('kur', 'kurna', 'kura') AND th_sys.org_id='{$orgId}', th_sys.therapie_systemisch_id, NULL)) > 0 OR
            COUNT(DISTINCT IF(th_str.intention IN ('kur', 'kurna', 'kura') AND th_str.org_id='{$orgId}', th_str.strahlentherapie_id, NULL)) > 0 OR
            COUNT(DISTINCT IF(th_son.intention IN ('kur', 'kurna', 'kura') AND th_son.org_id='{$orgId}', th_son.sonstige_therapie_id, NULL)) > 0,
            1,
            NULL
        )                                                                  AS 'kurativ_behandelt',

        {$this->_uArtCount(array('1-620.0', '8-100.4', '1-e24', '1-e25'))} AS anz_flexible_bronchoskopie,

        {$this->_uArtCount(array('1-620.1', '8-100.5', '1-e22', '1-e23'))} AS anz_starre_bronchoskopie,

        COUNT(DISTINCT IF(
            s.form = 'eingriff' AND SUBSTRING(s.report_param, 3, 1) IN ('B', sit.diagnose_seite),
            (SELECT eo.eingriff_id FROM eingriff_ops eo
                WHERE
                    eo.eingriff_id = s.form_id AND eo.prozedur_seite IN ('B', sit.diagnose_seite) AND
                    (LOCATE('5-339.2', eo.prozedur) != 0 OR
                     LOCATE('5-320.4', eo.prozedur) != 0)
                GROUP BY
                    eo.eingriff_id
            ),
            NULL
        ))                                                               AS 'anz_thermisch_endoskopische_verfahren',

        COUNT(DISTINCT IF(
            s.form = 'eingriff' AND SUBSTRING(s.report_param, 3, 1) IN ('B', sit.diagnose_seite),
            (SELECT eo.eingriff_id FROM eingriff_ops eo
                WHERE
                    eo.eingriff_id = s.form_id AND eo.prozedur_seite IN ('B', sit.diagnose_seite) AND
                    (LOCATE('5-339.03', eo.prozedur) != 0 OR
                     LOCATE('5-339.04', eo.prozedur) != 0 OR
                     LOCATE('5-339.05', eo.prozedur) != 0
                    )
                GROUP BY
                    eo.eingriff_id
            ),
            NULL
        ))                                                              AS 'anz_endoskopische_stenteinlagen',

        GROUP_CONCAT(DISTINCT
            IF(
                LOCATE('1-691.0', u.art) != 0 OR
                LOCATE('5-320.2', u.art) != 0 OR
                LOCATE('5-342.03', u.art) != 0,
                CONCAT_WS('#;#', u.datum, u.art),
                NULL
            )
        SEPARATOR '~#~')                                              AS 'endo_thora_untersuchungen',

        GROUP_CONCAT(DISTINCT
            IF(s.form = 'eingriff' AND SUBSTRING(s.report_param, 3, 1) IN ('B', sit.diagnose_seite),
            (SELECT CONCAT_WS('#;#', eo.eingriff_id, GROUP_CONCAT(DISTINCT IF(
                LOCATE('1-691.0', eo.prozedur) != 0 OR
                LOCATE('5-320.2', eo.prozedur) != 0 OR
                LOCATE('5-342.03', eo.prozedur) != 0,
                eo.prozedur,
                NULL
            ) SEPARATOR '+#+')) FROM eingriff_ops eo
                WHERE
                    eo.eingriff_id = s.form_id AND eo.prozedur_seite IN ('B', sit.diagnose_seite) AND
                    (LOCATE('1-691.0', eo.prozedur) != 0 OR
                     LOCATE('5-320.2', eo.prozedur) != 0 OR
                     LOCATE('5-342.03', eo.prozedur) != 0
                    )
                GROUP BY
                    eo.eingriff_id
            ),
            NULL)
        SEPARATOR '~#~')                                                AS 'endo_thora_eingriffe',

        GROUP_CONCAT(DISTINCT
            IF(op.eingriff_id IS NOT NULL,
                CONCAT_WS('|', op.eingriff_id, op.datum),
                NULL
        ))                                                              AS 'endo_thora_eingriff_identifier',

        0                                                               AS 'anz_endoskopische_thorakoskopien',

        GROUP_CONCAT(DISTINCT IF(
            s.form = 'eingriff' AND SUBSTRING(s.report_param, 3, 1) IN ('B', sit.diagnose_seite),
            (SELECT GROUP_CONCAT(DISTINCT eo.prozedur SEPARATOR '#+#') FROM eingriff_ops eo
                WHERE
                    eo.eingriff_id = s.form_id AND eo.prozedur_seite IN ('B', sit.diagnose_seite)
                GROUP BY
                    eo.eingriff_id
            ),
            NULL
        ) SEPARATOR '#+#')                                              AS 'ops_codes',

        GROUP_CONCAT(DISTINCT
            IF(tp.zeitpunkt = 'prae' AND tp.intention IS NOT NULL, CONCAT_WS(',', tp.datum, tp.intention), NULL)
            SEPARATOR '|'
        )                                                               AS 'ther_intention_praeth',

        GROUP_CONCAT(DISTINCT
            IF(tp.zeitpunkt = 'post' AND tp.intention IS NOT NULL, CONCAT_WS(',', tp.datum, tp.intention), NULL )
            SEPARATOR '|'
        )                                                               AS 'ther_intention_postop',

        IF(
            COUNT(DISTINCT IF(
                th_sys.vorlage_therapie_art IN ('ist', 'ci', 'i'),
                th_sys.therapie_systemisch_id,
                NULL
            ))
            OR
            COUNT(DISTINCT IF(
                th_str.vorlage_therapie_art = 'ist',
                th_str.strahlentherapie_id,
                NULL
            ))
            , 1, NULL
        )                                                               AS 'durchgef_immunth',

        GROUP_CONCAT(DISTINCT
            IF(LOCATE('c', th_sys.vorlage_therapie_art) != 0,
               CONCAT_WS('|', th_sys.vorlage_therapie_id, IFNULL(th_sys.intention,'--')),
            NULL
        )) AS 'acc_th_sys',

        GROUP_CONCAT(DISTINCT
            IF(LOCATE('c', th_str.vorlage_therapie_art) != 0,
               CONCAT_WS('|', th_str.vorlage_therapie_id, IFNULL(th_str.intention,'--')),
               NULL
        )) AS 'acc_th_str',

        NULL                                                            AS 'adjuvante_cisplatinhaltige_chemotherapie',

        IF(
            COUNT(DISTINCT IF(
                th_sys.vorlage_therapie_art = 'cst',
                th_sys.therapie_systemisch_id,
                null
            )) OR
            COUNT(DISTINCT IF(
                th_str.vorlage_therapie_art = 'cst',
                th_str.strahlentherapie_id,
                NULL
            ))
            , 1, null
        )                                                               AS 'kombinierte_radiochemotherapie',

        MIN(th_str.beginn)                                              AS 'beginn_strahlentherapie',

        IF(
            COUNT(
                IF(th_str.endstatus = 'plan',
                    th_str.strahlentherapie_id,
                    null
                )
            ) > 0, 1,
            IF(
                COUNT(
                    IF(th_str.endstatus IS NOT NULL,
                    th_str.strahlentherapie_id,
                    null
                    )
                ) > 0, 0, null
            )
        )                                                                   AS strahlenth_plan_abgeschlossen,

        IF(COUNT(
            IF(
                1 IN (
                    th_str.ziel_primaertumor,
                    th_str.ziel_brustwand_r,
                    th_str.ziel_brustwand_l,
                    th_str.ziel_mediastinum,
                    th_str.ziel_lymph,
                    th_str.ziel_knochen
                ) OR
                (th_str.ziel_sonst IS NOT NULL AND LEFT(th_str.ziel_sonst_detail, 3) IN ('C34','C38','C39')),
                1,
                NULL
            )) > 0,
            1,
            NULL
        )                                                                    AS 'thorakale_bestrahlung',

        IF(COUNT(IF(tp.chemo = '1' AND tp.chemo_intention IN('kura', 'pala'), tp.therapieplan_id, null)) > 0,
            1,
            IF(COUNT(IF(tp.chemo = '0', tp.therapieplan_id, null)) > 0, 0, null)
        )                                                  AS adj_chemoth_gepl,

        IF(COUNT(IF(tp.immun = '1' AND tp.immun_intention IN('kura', 'pala'), tp.therapieplan_id, null)) > 0,
            1,
            IF(COUNT(IF(tp.immun = '0', tp.therapieplan_id, null)) > 0, 0, null)
        )                                                  AS adj_immunth_gepl,

        IF(COUNT(IF(tp.strahlen = '1' AND tp.strahlen_intention IN('kura', 'kurna'), tp.therapieplan_id, null)) > 0,
            1,
            IF(COUNT(IF(tp.strahlen = '0', tp.therapieplan_id, null)) > 0, 0, null)
        )                                                  AS adj_strahlenth_gepl,

        MIN(IF(h.art = 'pr', h.datum, null))               AS datum_praeop_histologie,

        MIN(IF(op.art_primaertumor = 1 OR op.art_rezidiv = 1, op.datum, null))         AS datum_primaer_op_oder_rezidiv_op,

        MIN(IF(h.art = 'po', h.datum, null))               AS datum_postop_histologie,

        IF(
            COUNT(DISTINCT IF(
                op.art_primaertumor IS NOT NULL,
                (SELECT 1 FROM eingriff_ops eo
                    WHERE
                        eo.eingriff_id = op.eingriff_id AND eo.prozedur_seite IN ('B', sit.diagnose_seite) AND
                        ({$lungenresektion})
                    GROUP BY
                        eo.eingriff_id
                ),
                NULL
            )), 1, NULL
        )                                                                 AS 'lungenresektion_durchgefuehrt',

        IF(
            COUNT(DISTINCT IF(
                op.art_primaertumor IS NOT NULL,
                (SELECT 1 FROM eingriff_ops eo
                    WHERE
                        eo.eingriff_id = op.eingriff_id AND eo.prozedur_seite IN ('B', sit.diagnose_seite) AND
                        ({$pneumektomie} )
                    GROUP BY
                        eo.eingriff_id
                ),
                NULL
            )), 1, NULL
        )                                                                 AS pneumektomie,

        IF(
            COUNT(DISTINCT IF(
                op.art_primaertumor IS NOT NULL,
                (SELECT 1 FROM eingriff_ops eo
                    WHERE
                        eo.eingriff_id = op.eingriff_id AND eo.prozedur_seite IN ('B', sit.diagnose_seite) AND
                        ({$broncho} )
                    GROUP BY
                        eo.eingriff_id
                ),
                NULL
            )), 1, NULL
        )                                                                 AS broncho_op,

        IF(
            COUNT(DISTINCT IF(
                op.art_primaertumor IS NOT NULL,
                (SELECT 1 FROM eingriff_ops eo
                    WHERE
                        eo.eingriff_id = op.eingriff_id AND eo.prozedur_seite IN ('B', sit.diagnose_seite) AND
                        ({$anastomose} )
                    GROUP BY
                        eo.eingriff_id
                ),
                NULL
            )), 1, NULL
        )                                                                 AS anastomose,

        IF(COUNT(DISTINCT
            IF(
                k.komplikation IN ('bsi','ndo','ndt','ani'),
                k.komplikation_id,
                NULL
            )) > 0,
            1,
            NULL
        )                                                                AS anastomoseinsuffizienz,

        IF(
            COUNT(DISTINCT IF(
                op.art_primaertumor IS NOT NULL,
                (SELECT 1 FROM komplikation kk
                    WHERE
                        kk.eingriff_id = op.eingriff_id AND kk.komplikation IN ('wi','wa1','wa2','wa3','wctc2')
                    GROUP BY
                        kk.eingriff_id
                ),
                NULL
            )), 1, NULL
        )                                                                 AS wundinfektion,

        IF(
            COUNT(DISTINCT IF(
                op.art_primaertumor IS NOT NULL,
                (SELECT 1 FROM komplikation kk
                    WHERE
                        kk.eingriff_id = op.eingriff_id AND kk.revisionsoperation = 1 AND DATEDIFF(kk.datum, op.datum) <= 90
                    GROUP BY
                        kk.eingriff_id
                ),
                NULL
            )), 1, NULL
        )                                                                 AS revisions_op,

        MAX(IF(op.art_primaertumor IS NOT NULL, op.eingriff_id, NULL))    AS 'primaerop_eingriff_id',

        MAX(op.schnellschnitt)                                            AS 'intraop_schnellschnitt',

        MIN(op.schnellschnitt_dauer)                                      AS 'dauer_probe_versand_durchsage',

        sit.ct                                                            AS 'ct',
        sit.cn                                                            AS 'cn',
        sit.pt                                                            AS 'pt',
        sit.pn                                                            AS 'pn',
        sit.t                                                             AS 't',
        sit.n                                                             AS 'n',
        sit.m                                                             AS 'm',

        ## Beachten: UICC wird am Ende nochmals nachverarbeitet! ##
        sit.uicc_prae                                      AS 'uicc_praetherapeutisch',
        sit.uicc                                           AS 'uicc',

        ## Beachten: UICC wird am Ende nochmals nachverarbeitet! ##
        null                                                                    AS 'uicc_nach_neoadj_th',

        sit.morphologie                                    As 'morphologie',
        sit.g                                              AS 'g',
        sit.r                                              AS 'r',
        sit.r_lokal                                        AS 'r_lokal',
        sit.lk_bef                                         AS 'lk_befallen',
        sit.lk_entf                                        AS 'lk_entfernt',

        sit.resektionsrand                                 AS 'sicherheitsabstand',

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
         )                                                 AS praeth_konferenz,
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
         )                                                 AS postop_konferenz,

        GROUP_CONCAT(DISTINCT
               IF(s.form = 'studie',
                   CONCAT_WS('|', s.form_id, DATE_FORMAT(s.report_param, '%d.%m.%Y')),
                   NULL
               )
               SEPARATOR ', '
            )                                                                               AS 'datum_studie',

        COUNT(DISTINCT IF(s.form = 'studie', s.form_id, NULL))                              AS 'anzahl_studienteilnahme',

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
        )                                                                                   AS psychoonk_betreuung,

        MAX(DISTINCT b.sozialdienst)                                                        AS beratung_sozialdienst,

        GROUP_CONCAT(DISTINCT CONCAT_WS(',', n.datum, n.response_klinisch) SEPARATOR '|')   AS response,

        MAX(n.datum)                                                                        AS datum_letzte_nachsorge,

        IF(sit.rezidiv_lokal_datum IS NOT NULL AND sit.rezidiv_lk_datum IS NOT NULL,
           IF(
              sit.rezidiv_lokal_datum < sit.rezidiv_lk_datum,
              sit.rezidiv_lokal_datum,
              sit.rezidiv_lk_datum
           ),
           IFNULL(sit.rezidiv_lokal_datum, sit.rezidiv_lk_datum)
        )                                                                                   AS datum_lokalrezidiv,

        sit.rezidiv_metastasen_datum                                                        AS datum_metastasen,

        IF(
            MAX(x.abschluss_grund) IS NOT NULL AND MAX(x.abschluss_grund) = 'lost',
            1,
            IF(
               MAX(x.abschluss_grund) IS NOT NULL AND MAX(x.abschluss_grund) != 'lost',
               0,
               NULL
            )
        )                                                                                   AS lost_to_fu,
        IF(
            MAX(x.tod_tumorassoziation) IS NOT NULL AND MAX(x.tod_tumorassoziation) IN ('tott', 'totn'),
            1,
            IF(
               MAX(x.tod_tumorassoziation) IS NOT NULL AND MAX(x.tod_tumorassoziation) NOT IN ('tott', 'totn'),
               0,
               NULL
            )
        )                                                                                   AS tod_tumorbedingt,

        MAX(x.todesdatum)                                                                   AS todesdatum,

        CONCAT_WS('|',
            MIN(IF(s.form = 'sonstige_therapie', s.form_date, NULL)),
            MIN(th_sys.beginn),
            MIN(th_str.beginn),
            MIN(IF('1' IN (op.art_primaertumor, op.art_lk,op.art_metastasen,op.art_rezidiv,op.art_nachresektion,op.art_revision), op.datum, NULL)),
            '9999-12-31'
         )                                                                           AS 'max_uicc',

         sit.anlass,
         sit.erkrankung_id,
         sit.patient_id,
         sit.start_date,
         sit.end_date

      FROM ($preQuery) sit
         {$this->_innerStatus()}
         {$this->_statusJoin('anamnese a')}

         LEFT JOIN histologie h ON s.form = 'histologie' AND h.histologie_id  = s.form_id AND
                                   h.diagnose_seite IN ('B', sit.diagnose_seite)

         LEFT JOIN eingriff op ON s.form = 'eingriff' AND op.eingriff_id = s.form_id
                                  AND op.diagnose_seite IN ('B', sit.diagnose_seite)

         LEFT JOIN untersuchung u ON s.form = 'untersuchung' AND u.untersuchung_id = s.form_id
                                     AND u.art_seite IN ('B', sit.diagnose_seite)

         {$this->_statusJoin('beratung b')}
         {$this->_statusJoin('therapie_systemisch th_sys')}
         {$this->_statusJoin('strahlentherapie th_str')}
         {$this->_statusJoin('sonstige_therapie th_son')}
         {$this->_statusJoin('therapieplan tp')}
         {$this->_statusJoin('komplikation k')}
         {$this->_statusJoin('abschluss x')}

         LEFT JOIN nachsorge n  ON s.form = 'nachsorge' AND LOCATE(CONCAT_WS('','-',sit.erkrankung_id,'-'), s.report_param) > 0 AND
                                   n.nachsorge_id = s.form_id
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
         nachname, vorname, bezugsdatum
    ";

    $data = sql_query_array($this->_db, $query);

    $lKomplikation = getLookup($this->_db, 'komplikation');

    foreach ($data as $i => $dataset) {

        $diseaseId = (int) $dataset['erkrankung_id'];
        $case = $dataset['anlass'];
        $side = $dataset['seite'];

        $primaryCase = array_key_exists($diseaseId . $case . $side, $primaryCases) === true ? $primaryCases[$diseaseId . $case . $side]['primaerfall'] : null;

        // MUST befor recidiv check!!!
        if ('p' == $case && '0' == $primaryCase) {
            $data[$i]['anlass_case'] .= " (synchron)";
        }

        if ($primaryCase === null && str_starts_with($case, 'r') === true) {
            $primaryCase = '0';
        }

        $data[$i]['primaerfall']  = $primaryCase;

        //Endoskopische thorakale Untersuchung/Eingriff
        if (strlen($dataset['endo_thora_eingriffe']) > 0 || strlen($dataset['endo_thora_untersuchungen']) > 0) {
            $data[$i]['anz_endoskopische_thorakoskopien'] = $this->_calcThoraskopien(
                $dataset['endo_thora_untersuchungen'],
                $dataset['endo_thora_eingriffe'],
                $dataset['endo_thora_eingriff_identifier']
            );
        }

        $data[$i]['ecog']                   = $this->_selectMaxByDate($dataset['ecog']);
        $data[$i]['ops_codes']              = $this->_distinct($dataset['ops_codes'], '#+#', ', ');
        $data[$i]['ther_intention_praeth']  = $this->_translateLookup(
            $this->_selectMaxByDate($dataset['ther_intention_praeth']),
            'intention_gesamt'
        );



        $data[$i]['ther_intention_postop']  = $this->_translateLookup(
                $this->_selectMaxByDate($dataset['ther_intention_postop']),
                'intention_gesamt'
        );

        if (strlen($dataset['acc_th_str']) > 0 || strlen($dataset['acc_th_sys']) > 0) {
            $accThStr = strlen($dataset['acc_th_str']) ? explode(',', $dataset['acc_th_str']) : array();
            $accThSys = strlen($dataset['acc_th_sys']) ? explode(',', $dataset['acc_th_sys']) : array();

            $acc = array_unique(array_merge($accThStr, $accThSys));

            $val = 0;

            foreach ($acc AS $therapy) {
               $tmp = explode('|', $therapy);
               $pCisplatinVorlageId = reset($tmp);
               $isCond = (in_array(end($tmp), array('kura', 'pala')) === true);

               if (in_array($pCisplatinVorlageId, $cisplatinVorlagen) === true && $isCond === true) {
                    $val = 1;
                    break;
                }
            }

            $data[$i]['adjuvante_cisplatinhaltige_chemotherapie'] = $val;
        }

        unset($data[$i]['acc_th_str']);
        unset($data[$i]['acc_th_sys']);

        //UICC Berechnung
        $data[$i]['uicc_praetherapeutisch'] = $stageCalc->calcToMaxDate($dataset['uicc_praetherapeutisch'], min(explode('|', $dataset['max_uicc'])));
        $data[$i]['uicc']                   = $stageCalc->calc($dataset['uicc']);
        $data[$i]['uicc_nach_neoadj_th']    = $stageCalc->getCacheValue('tnm_praefix');

        $data[$i]['datum_studie']           = $this->_removeIdentifier($dataset['datum_studie']);
        $data[$i]['response']               = $this->_selectMinByDate($dataset['response']);

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
