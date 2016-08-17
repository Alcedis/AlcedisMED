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


    $having = "(LEFT(diagnose,3) IN ('C48','C51','C52','C53','C54','C55','C56','C57','C58', 'D06') OR
               diagnose IN ('C79.82', 'D07.0', 'D07.1') OR
               (diagnose = 'D39.1' AND g IS NOT NULL)
            )"
    ;

    $relevantSelectWhere = "ts.erkrankung_id = t.erkrankung_id AND ts.anlass = t.anlass";

    $relevantSelects = array_merge(
        array($this->_notCountSelect()),
        $additionalTsSelects,
        array(
            "(SELECT ts.g FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.g IS NOT NULL ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS g"
        )
    );

    $preQuery = $this->_getPreQuery($having, $relevantSelects);

    //Special date filter for gt03 only
    $dateF = $this->_params['name'] == 'gt03' ? $this->_buildHaving() : $this->_buildHaving('op_datum');

    $query = "
        SELECT
            sit.erkrankung_id,
            sit.patient_id,
            op.eingriff_id,
            {$additionalFields}
            sit.nachname,
            sit.vorname,
            sit.geburtsdatum,
            sit.patient_nr                                         AS 'patient_nr',
            IF(sit.anlass = 'p', 1, 0)                             AS 'primaerfall',
            {$this->_getAnlassCases()}                             AS 'anlass_case',
            IF(
                 sit.anlass LIKE 'r%' AND MIN(hx.datum) IS NULL,
                 IF(sit.start_date = '0000-00-00', sit.start_date_rezidiv, sit.start_date),
                 MIN(hx.datum)
            )                                                      AS 'bezugsdatum',
            op.datum                                               AS 'op_datum',
            sit.diagnose                                           AS 'diagnose',
            REPLACE(SUBSTRING(s.report_param, 5), ' ', ', ')       AS 'ops_codes',
            IF(
                sit.g IS NOT NULL,
                (SELECT bez FROM l_basic WHERE klasse = 'g' AND code = sit.g)
                ,
                NULL
            )                                                      AS 'g',
            IF(sit.diagnose = 'D39.1' AND (sit.g IS NOT NULL AND sit.g != 'B'), 1, NULL)  AS 'eingriff_ovar',
            IF(LEFT(sit.diagnose,3) = 'D06', 1, NULL)              AS 'eingriff_zervix',
            IF(sit.diagnose = 'D07.0', 1, NULL)                    AS 'eingriff_endometrium',
            IF(sit.diagnose = 'D07.1', 1, NULL)                    AS 'eingriff_vulva'
        FROM ({$preQuery}) sit
            {$this->_innerStatus()}
            {$this->_statusJoin('eingriff op')}
            LEFT JOIN histologie hx                 ON hx.erkrankung_id = op.erkrankung_id      AND hx.datum  BETWEEN sit.start_date AND sit.end_date
        WHERE
            {$this->_getNcState()}
        GROUP BY
            op.eingriff_id
        HAVING
            {$dateF} AND op.eingriff_id IS NOT NULL
            {$additionalCondition}
        ORDER BY
            nachname,
            vorname,
            bezugsdatum,
            op_datum
    ";

    $data = sql_query_array($this->_db, $query);
?>