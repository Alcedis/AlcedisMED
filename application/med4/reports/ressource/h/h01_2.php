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

    $stageCalc = stageCalc::create($this->_db, $this->_params['sub']);

    /**
     * large code arrays
     */
    $mikroCodes = '5-091.11|5-091.31|5-181.1|5-181.4|5-181.6|5-182.1|5-182.3|5-212.1|5-213.1|5-213.3|5-213.5|5-895.1|5-895.3|5-895.5';
    $obturCodes = '5-402.3|5-402.8|5-404.d|5-404.e|5-406.3|5-407.2|5-402.6|5-402.b|5-406.6';

    /**
     * parse data
     */
    $sentinel        = $this->_eingriffCase(explode('|', '3-760|5-401.01|5-401.02|5-401.11|5-401.12|5-401.51|5-401.52'));
    $mikroChir       = $this->_eingriffCase(explode('|', $mikroCodes));
    $lymphInguinal   = $this->_eingriffCase(explode('|', '5-402.4|5-402.9|5-404.h|5-406.4|5-407.4'));
    $lymphObtur      = $this->_eingriffCase(explode('|', $obturCodes));
    $lymphAx         = $this->_eingriffCase(explode('|', '5-402.1|5-406.1|5-871|5-870.a|5-873|5-875|5-874.0'));
    $lymphZerv       = $this->_eingriffCase(explode('|', '5-402.0|5-403|5-406.0'));

    $additionalTsSelects[] = $stageCalc->select('c', 'ajcc')  . "AS 'ajcc_prae'";
    $additionalTsSelects[] = $stageCalc->select(null, 'ajcc') . "AS 'ajcc'";
    $additionalTsSelects[] = 'e.erkrankung_relevant_haut';

    $preQuery        = $this->_getPreQuery('diagnose LIKE "C%"', array_merge(array($this->_notCountSelect()), $additionalTsSelects));

    $datumsbezug = $this->getParam('datumsbezug');

    $dateField = $datumsbezug === 'histo' ? 'bezugsdatum' : 'op_datum';

    //Special date filter for h02 only
    $dateF = $this->_params['name'] == 'h02' ? '1' : $this->_buildHaving($dateField);

    //Breslow >= 1
    $this->_addPreSelect('breslowgr11_02', "
       SELECT
           DISTINCT histologie_id AS 'id'
       FROM histologie_einzel
       WHERE
           erkrankung_id IN ({$this->_filteredDiseases}) AND
           tumordicke >= 1
   ");

    $primaryCases = $this->_detectPrimaryCases($preQuery);

    $query = "
        SELECT
            op.*,
            {$sentinel}                                             AS sentinel_node_biopsie,
            IF(
                LEFT(op.sln_markierung, 2) = '99',
                IF(op.sln_anzahl > 0, 1,
                    IF(op.sln_anzahl = 0, 0, NULL)
                ),
                NULL
            )
                                                                   AS gammasonde_sentinel,
            IF(COUNT(he.histologie_einzel_id) > 0,
                IF(COUNT(IF(he.resektionsrand IS NULL, he.histologie_einzel_id, NULL)) > 0,
                    NULL,
                    IF(MIN(he.resektionsrand) > 0,
                        1,
                        0
                    )
                ),
                NULL
            )                                                       AS sicherheitsabstand,

            {$mikroChir}                                            AS mikrograph_chirurgie,
            {$lymphInguinal}                                        AS sys_lymph_inguinal,
            {$this->_countLk('lk_inguinal_entf')}                   AS anz_lk_entf_inguinal,
            {$lymphObtur}                                           AS sys_lymph_iliakal_obtur,
            {$this->_countLk('lk_iliakal_entf')}                    AS anz_lk_entf_iliakal_obtur,
            {$lymphAx}                                              AS sys_lymph_axillaer,
            {$this->_countLk('lk_axillaer_entf')}                   AS anz_lk_entf_axillaer,
            {$lymphZerv}                                            AS sys_lymph_zervikal,
            {$this->_countLk('lk_zervikal_entf')}                   AS anz_lk_entf_zervikal,
            IF(
                COUNT(IF(k.revisionsoperation = 1 AND DATEDIFF(k.datum, op.op_datum) <= 90, k.komplikation_id, NULL)) > 0,
                1,
                IF(
                    COUNT(IF(k.revisionsoperation = 0, k.komplikation_id, NULL)) > 0 OR
                    COUNT(IF(k.revisionsoperation = 1 AND DATEDIFF(k.datum, op.op_datum) > 90, k.komplikation_id, NULL)) > 0,
                    0,
                    NULL
                )
            )                                                       AS revisions_op,
            IF(
                COUNT(IF(
                    k.komplikation IN ('wi','wa1','wa2','wa3','wctc2'),
                    k.komplikation_id,
                    NULL
                )) > 0,
                1,
                IF(
                    MAX(k.komplikation_id) IS NOT NULL,
                    0,
                    NULL
                )
            )                                                       AS wundinfektion,
            GROUP_CONCAT(DISTINCT k.komplikation SEPARATOR '|')     AS komplikation
        FROM (
            SELECT
                sit.erkrankung_id,
                op.eingriff_id,
                op.sln_markierung,
                op.sln_anzahl,
                {$additionalFields}
                sit.nachname                                        AS nachname,
                sit.vorname                                         AS vorname,
                sit.geburtsdatum                                    AS geburtsdatum,
                null                                                AS primaerfall,
                sit.patient_nr                                      AS patient_nr,
                sit.anlass                                          AS anlass,
                {$this->_getAnlassCases()}                          AS situation,
                sit.diagnose                                        AS diagnose,
                IF((sit.diagnose LIKE 'C43%' AND (
                    sit.morphologie != '8247/3' AND
                    sit.morphologie != '9120/3' AND
                    sit.morphologie != '8832/3' AND
                    sit.morphologie != '8833/3') OR
                    (sit.diagnose LIKE 'C43%' AND
                    sit.morphologie IS NULL)
                ), 1, 0)                                            AS invasives_malignom,

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
                )                                                  AS epithelialer_tumor,

                -- wird unten nochmal nachvearbeitet
                IF(
                    sit.morphologie = '8247/3' OR
                    sit.morphologie = '9120/3' OR
                    sit.morphologie = '8832/3' OR
                    sit.morphologie = '8833/3',
                    1,
                    0
                )                                                   AS seltene_tumore,
                sit.morphologie                                     AS morphologie,

                IF(
                    sit.anlass LIKE 'r%' AND MIN(hx.datum) IS NULL,
                    IF(sit.start_date = '0000-00-00', sit.start_date_rezidiv, sit.start_date),
                    MIN(hx.datum)
                )
                                                                    AS bezugsdatum,
                sit.ajcc_prae                                       AS ajcc_prae,
                sit.ajcc                                            AS ajcc,
                op.datum                                            AS op_datum,
                op.art_diagnostik                                   AS diag_eingriff,
                op.art_primaertumor                                 AS resektion_primaertumor,
                op.art_lk                                           AS resektion_loko_lymph,
                op.art_metastasen                                   AS resektion_metast,
                op.art_rezidiv                                      AS resektion_loko_rezidiv,
                op.art_nachresektion                                AS nachresektion,
                op.art_revision                                     AS revisions_op_komp,
                op.art_sonstige                                     AS sonstiger_eingriff,
                MAX(
                    IF(s.form = 'eingriff' AND s.form_id = op.eingriff_id,
                        REPLACE(SUBSTRING(s.report_param, 5), ' ', ', '),
                        NULL
                    )
                )                                                   AS ops_codes,
                CONCAT_WS('|',
                    MIN(sotx.beginn),
                    MIN(tsx.beginn),
                    MIN(stx.beginn),
                    MIN(IF('1' IN (op.art_primaertumor, op.art_lk,op.art_metastasen,op.art_rezidiv,op.art_nachresektion,op.art_revision), op.datum, NULL)),
                    '9999-12-31'
                )                                                   AS 'max_ajcc',
                IF(COUNT(IF(
                        hx.histologie_id IN ({$this->_getPreSelect('breslowgr11_02')}),
                        hx.histologie_id,
                        NULL)
                    ) > 0,
                    1,
                    NULL
                )                                                   AS tumordicke_gr1mm
            FROM ($preQuery) sit
                {$this->_innerStatus()}
                {$this->_statusJoin('eingriff op')} AND LENGTH(SUBSTRING(s.report_param, 5)) > 0

                    LEFT JOIN histologie hx ON hx.erkrankung_id = op.erkrankung_id AND hx.datum BETWEEN sit.start_date AND sit.end_date
                    LEFT JOIN therapie_systemisch tsx ON tsx.erkrankung_id = op.erkrankung_id AND tsx.beginn BETWEEN sit.start_date AND sit.end_date
                    LEFT JOIN strahlentherapie stx ON stx.erkrankung_id = op.erkrankung_id AND stx.beginn BETWEEN sit.start_date AND sit.end_date
                    LEFT JOIN sonstige_therapie sotx ON sotx.erkrankung_id = op.erkrankung_id AND sotx.beginn BETWEEN sit.start_date AND sit.end_date
            WHERE
               {$this->_getNcState()}
            GROUP BY
               op.eingriff_id
            HAVING
               {$dateF} AND LENGTH(ops_codes) > 0
               {$additionalCondition}
            ORDER BY
               null
        ) op
            LEFT JOIN komplikation k    ON k.erkrankung_id = op.erkrankung_id AND k.eingriff_id = op.eingriff_id
            LEFT JOIN histologie h      ON h.erkrankung_id = op.erkrankung_id AND h.eingriff_id = op.eingriff_id
                LEFT JOIN histologie_einzel he ON he.histologie_id = h.histologie_id
        GROUP BY
            op.eingriff_id
        ORDER BY
            nachname,
            vorname,
            bezugsdatum,
            op_datum
    ";

    $data = sql_query_array($this->_db, $query);

    foreach ($data as $i => $dataset) {
        //Convert lk count
        $data[$i]['anz_lk_entf_inguinal']       = $this->_takeNewestLk($data[$i]['anz_lk_entf_inguinal']);
        $data[$i]['anz_lk_entf_iliakal_obtur']  = $this->_takeNewestLk($data[$i]['anz_lk_entf_iliakal_obtur']);
        $data[$i]['anz_lk_entf_axillaer']       = $this->_takeNewestLk($data[$i]['anz_lk_entf_axillaer']);
        $data[$i]['anz_lk_entf_zervikal']       = $this->_takeNewestLk($data[$i]['anz_lk_entf_zervikal']);

        //AJCC Berechnung
        $data[$i]['ajcc_prae'] = $stageCalc->calcToMaxDate($dataset['ajcc_prae'], min(explode('|', $dataset['max_ajcc'])));
        $data[$i]['ajcc']      = $stageCalc->calc($dataset['ajcc']);

        unset($data[$i]['max_ajcc']);

        if ($dataset['seltene_tumore'] == 0) {
            if (str_starts_with($dataset['diagnose'], 'C') === true && $dataset['invasives_malignom'] == 0 && $dataset['epithelialer_tumor'] == 0) {
                $data[$i]['seltene_tumore'] = '1';
            }
        }

        $case = $dataset['anlass'];
        $diseaseId = $dataset['erkrankung_id'];

        $primaryCase = array_key_exists($diseaseId . $case, $primaryCases) === true ? $primaryCases[$diseaseId . $case] : null;

        if ($primaryCase === null && str_starts_with($case, 'r') === true) {
            $primaryCase = 0;
        }

        $data[$i]['primaerfall'] = $primaryCase;
    }

?>
