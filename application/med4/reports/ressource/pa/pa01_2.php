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

    $relevantSelects = array_merge(
        array($this->_notCountSelect()),
        $additionalTsSelects
    );

    $this->resetDiseaseFilter();
    $this->_filterDisease(true);

    $preQuery = $this->_getPreQuery(null, $relevantSelects);

    $dateF = $this->_params['name'] == 'pa02' ? '1' : $this->_buildHaving('op_datum');

    $query = "
        SELECT
            e.*,
            IF(MAX(revop.eingriff_id) IS NOT NULL, 1, NULL)         AS 'op_revision',
            COUNT(DISTINCT k.komplikation_id)                       AS 'wundinfektion30tage',
            GROUP_CONCAT(DISTINCT ab.abschluss_id)                  AS 'tod30tage',
            CONCAT_WS(', ', org.ort, org.name)                      AS 'org'
        FROM (SELECT
                sit.erkrankung_id,
                op.eingriff_id,
                sit.patient_id,
                {$additionalFields}
                sit.nachname                                        AS nachname,
                sit.vorname                                         AS vorname,
                sit.geburtsdatum                                    AS geburtsdatum,
                sit.patient_nr                                      AS patient_nr,
                IF(sit.anlass = 'p',
                    IF(sit.morphologie NOT IN ('8453/1', '8453/2', '8453/3'), 1, 0),
                    0
                )                                                   AS primaerfall,
                {$this->_getAnlassCases()}                          AS anlass_case,
                IF(
                    sit.anlass LIKE 'r%' AND MIN(hx.datum) IS NULL,
                    IF(sit.start_date = '0000-00-00', sit.start_date_rezidiv, sit.start_date),
                    MIN(hx.datum)
                )                                                   AS bezugsdatum,

                sit.diagnose                                        AS diagnose,

                op.datum                                            AS op_datum,
                REPLACE(SUBSTRING(s.report_param, 5), ' ', ', ')    AS ops_codes,

                IF(op.art_primaertumor IS NOT NULL AND
                    (LOCATE('5-524', SUBSTRING(s.report_param, 5)) != 0 OR
                     LOCATE('5-525', SUBSTRING(s.report_param, 5)) != 0
                    ) AND sit.diagnose LIKE 'C25%',
                    1,
                    NULL
                )                                                   AS op_primaertumor,
                NULL AS 'org',
                IF(LOCATE('5-524', SUBSTRING(s.report_param, 5)) != 0 OR
                   LOCATE('5-525', SUBSTRING(s.report_param, 5)) != 0
                   ,
                   1,
                   NULL
                )                                                   AS pankreasresektion,
                op.notfall                                          AS op_notfall,
                sit.start_date,
                sit.end_date,
                op.art_primaertumor,
                op.art_rezidiv,
                op.art_revision,
                op.org_id
            FROM ($preQuery) sit
                {$this->_innerStatus()}
                INNER JOIN eingriff op ON s.form = 'eingriff' AND op.eingriff_id = s.form_id
                    LEFT JOIN histologie hx   ON hx.erkrankung_id = op.erkrankung_id AND hx.datum BETWEEN sit.start_date AND sit.end_date
            WHERE
               {$this->_getNcState()}
            GROUP BY
                 op.eingriff_id
            HAVING
                {$dateF}
                {$additionalCondition} AND
                pankreasresektion IS NOT NULL
            ) e
            LEFT JOIN eingriff revop ON e.pankreasresektion IS NOT NULL AND
                                        revop.erkrankung_id = e.erkrankung_id AND
                                        e.eingriff_id != revop.eingriff_id AND
                                        revop.art_revision IS NOT NULL AND
                                        revop.datum BETWEEN e.start_date AND e.end_date AND
                                        DATEDIFF(revop.datum, e.op_datum) BETWEEN 0 AND 30

            LEFT JOIN komplikation k ON e.pankreasresektion IS NOT NULL AND
                                        k.eingriff_id = e.eingriff_id AND
                                        k.komplikation IN ('wi','wa1','wa2','wa3','wctc2') AND
                                        1 IN (k.wund_vac, k.wund_spuelung, wund_spreizung) AND
                                        DATEDIFF(k.datum, e.op_datum) BETWEEN 0 AND 30

            LEFT JOIN abschluss ab   ON ab.patient_id = e.patient_id AND
                                        ab.abschluss_grund = 'tot' AND
                                        DATEDIFF(ab.todesdatum, e.op_datum) BETWEEN 0 AND 30

            LEFT JOIN org org          ON e.org_id = org.org_id

        GROUP BY
            e.eingriff_id
        ORDER BY
            e.nachname,
            e.vorname,
            e.bezugsdatum,
            e.op_datum

     ";

    $data = sql_query_array($this->_db, $query);

    if ($this->_params['name'] == 'pa01_2') {
        foreach ($data as $i => $op) {
            $data[$i]['tod30tage'] = strlen($data[$i]['tod30tage']) ? '1' : null;
            unset($data[$i]['org_id']);
            unset($data[$i]['pankreasresektion']);
        }
    }
?>
