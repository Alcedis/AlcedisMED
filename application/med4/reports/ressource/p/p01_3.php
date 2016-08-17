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

$relevantSelects = array_merge(
    $additionalTsSelects,
    array(
        "(
            SELECT
                IF(
                    MAX(ts.nur_zweitmeinung) IS NOT NULL OR
                    MAX(ts.nur_diagnosesicherung) IS NOT NULL OR
                    MAX(ts.kein_fall) IS NOT NULL,
                    1,
                    NULL
                )
            FROM
                tumorstatus ts
            WHERE
                {$relevantSelectWhere}
        ) AS primaer",

        "(
            SELECT
                ts.zufall
            FROM
                tumorstatus ts
            WHERE
                {$relevantSelectWhere} AND
                ts.zufall IS NOT NULL
            ORDER BY
                ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1
        ) AS zufall",
    )
);


//$this->resetDiseaseFilter();
$this->_filterDisease(true);

$preQuery = $this->_getPreQuery("diagnose = 'C61'", $relevantSelects);

$dateF = $this->_params['name'] == 'pa02' ? '1' : $this->_buildHaving('op_datum');

$query = "
    SELECT
        e.*,
        IF(MIN(k.datum) IS NOT NULL, k.datum, NULL)             AS 'op_revision',
        IF(MIN(k.datum) IS NOT NULL, k.komplikation, NULL)      AS 'komplikation_code',
        IF(k.komplikation IS NOT NULL,
            (SELECT bez FROM l_basic WHERE klasse = 'komplikation' AND code = k.komplikation),
            NULL)                                               AS 'komplikation',
        CONCAT_WS(', ', org.ort, org.name)                      AS 'org'
    FROM (
        SELECT
            sit.erkrankung_id,
            op.eingriff_id,
            sit.patient_id,
            {$additionalFields}
            sit.nachname                                        AS 'nachname',
            sit.vorname                                         AS 'vorname',
            sit.geburtsdatum                                    AS 'geburtsdatum',
            IF(
                sit.primaer IS NULL AND
                sit.diagnose = 'C61' AND
                sit.anlass = 'p'
                AND (
                    COUNT(DISTINCT IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = 1, s.form_id, NULL)) > 0 OR
                    COUNT(DISTINCT th_sys.therapie_systemisch_id) > 0 OR
                    COUNT(DISTINCT th_str.strahlentherapie_id) > 0 OR
                    COUNT(DISTINCT IF('1' IN (tp.watchful_waiting, tp.active_surveillance), tp.therapieplan_id, NULL)) > 0
                ),
                1,
                null
            )                                                   AS 'primaerfall',
            {$this->_getAnlassCases()}                          AS 'anlass_case',

            sit.diagnose                                        AS 'diagnose',

            op.datum                                            AS 'op_datum',

            REPLACE(SUBSTRING(s.report_param, 5), ' ', ', ')    AS 'ops_codes',

            NULL                                                AS 'org',

            op.notfall                                          AS 'op_notfall',

            IF(LOCATE('5-604', SUBSTRING(s.report_param, 5)) != 0,
            1,
            NULL)                                               AS 'rpe',

            IF(LOCATE('5-576.2', SUBSTRING(s.report_param, 5)) != 0 OR
                LOCATE('5-576.3', SUBSTRING(s.report_param, 5)) != 0 OR
                LOCATE('5-576.4', SUBSTRING(s.report_param, 5)) != 0 OR
                LOCATE('5-576.5', SUBSTRING(s.report_param, 5)) != 0,
            1,
            NULL)                                               AS 'rze',

            sit.zufall                                          AS 'zufall',

            null                                                AS 'op_revision',
            null                                                AS 'zeitraum',
            null                                                AS 'komplikation',
            0                                                   AS 'anzahl_komplikation',

                sit.start_date,
                sit.end_date,
                op.org_id
            FROM ($preQuery) sit
                {$this->_innerStatus()}
                INNER JOIN eingriff op ON s.form = 'eingriff' AND op.eingriff_id = s.form_id
                    LEFT JOIN histologie hx              ON hx.erkrankung_id = op.erkrankung_id AND hx.datum BETWEEN sit.start_date AND sit.end_date
                    LEFT JOIN strahlentherapie th_str    ON th_str.erkrankung_id = sit.erkrankung_id
                    LEFT JOIN therapie_systemisch th_sys ON th_sys.erkrankung_id = sit.erkrankung_id
                    LEFT JOIN therapieplan tp            ON tp.erkrankung_id = sit.erkrankung_id AND (tp.org_id IS NULL OR tp.org_id = '{$this->_params['org_id']}')
            WHERE sit.erkrankung = 'p' AND
                (
                    LOCATE('5-604', SUBSTRING(s.report_param, 5)) != 0 OR
                    LOCATE('5-576.2', SUBSTRING(s.report_param, 5)) != 0 OR
                    LOCATE('5-576.3', SUBSTRING(s.report_param, 5)) != 0 OR
                    LOCATE('5-576.4', SUBSTRING(s.report_param, 5)) != 0 OR
                    LOCATE('5-576.5', SUBSTRING(s.report_param, 5)) != 0
                )
            GROUP BY
                 op.eingriff_id
            HAVING
                {$dateF}
                {$additionalCondition}
            ) e
            LEFT JOIN komplikation k ON k.erkrankung_id = e.erkrankung_id AND
                                        k.revisionsoperation = 1 AND
                                        k.eingriff_id = e.eingriff_id

            LEFT JOIN org org          ON e.org_id = org.org_id

        GROUP BY
            e.eingriff_id
        ORDER BY
            e.nachname,
            e.vorname,
            e.op_datum
     ";


$data = sql_query_array($this->_db, $query);

foreach ($data as $i => $op) {
    if (strlen($op['op_revision']) > 0) {
        $data[$i]['zeitraum'] = $diff = date_diff_days($op['op_datum'], $op['op_revision']);

        if ($op['rpe'] === '1' && ($diff >= 0 && $diff <= 90) && in_array($op['komplikation_code'], array('ane', 'darm', 'Harnver', 'lzb','nbl')) === true) {
            $data[$i]['anzahl_komplikation'] = '1';
        }
    }
}

?>


