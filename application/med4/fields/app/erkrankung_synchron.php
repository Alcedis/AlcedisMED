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

$filter = dlookup($db, 'erkrankung', 'erkrankung', "erkrankung_id = '{$erkrankung_id}'");

$diseaseDescription = dlookup($db, 'l_basic', 'bez', "klasse = 'erkrankung' AND code = '{$filter}'");

$tsQuery = "
    SELECT
        ts.erkrankung_id,
        ts.datum_sicherung,
        ts.diagnose
    FROM tumorstatus ts
        INNER JOIN erkrankung e ON e.erkrankung_id = ts.erkrankung_id AND e.erkrankung = '{$filter}'
    WHERE
        ts.patient_id = '{$patient_id}' AND
        ts.erkrankung_id != '{$erkrankung_id}' AND
        ts.anlass = 'p'
";

$diseases = array();

$querySync = array();

foreach (sql_query_array($db, $tsQuery) as $record) {
    $diseases[$record['erkrankung_id']][$record['datum_sicherung']] = $record;
}

foreach ($diseases as $tsRecords) {
    ksort($tsRecords);

    $tsRecord = reset($tsRecords);

    $querySync[] = "(SELECT
        '{$tsRecord['erkrankung_id']}',
        '{$diseaseDescription}',
        '{$tsRecord['diagnose']}',
        DATE_FORMAT('{$tsRecord['datum_sicherung']}', '%d.%m.%Y')
    )";
}

if (count($querySync) > 0) {
    $querySync = implode('UNION', $querySync);
} else {
    // This is only a dummy
    $querySync = "SELECT 1 FROM erkrankung WHERE erkrankung_id = false";
}

$fields = array(
    'erkrankung_synchron_id' => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
    'erkrankung_id'          => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
    'patient_id'             => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
    'erkrankung_synchron'    => array('req' => 1, 'size' => '',   'maxlen' => '',   'type' => 'query',    'ext' =>  $querySync),
    'createuser'             => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
    'createtime'             => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
    'updateuser'             => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
    'updatetime'             => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => '')
);

?>
