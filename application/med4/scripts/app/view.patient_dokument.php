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

$_SESSION['origin'] = array(
    'patient_id'      => $patient_id,
    'page'            => 'view.patient'
);

$rechte = isset($_SESSION['sess_recht_erkrankung']) === true ? implode("','", $_SESSION['sess_recht_erkrankung']) : '';

$erkQuery = sql_query_array($db, "SELECT code, bez FROM l_basic WHERE klasse = 'erkrankung' OR klasse = 'erkrankung_sst_detail'");

$erkrankungen = array();

foreach ($erkQuery as $erkData) {
    $erkrankungen[$erkData['code']] = $erkData['bez'];
}

$queryDokument = "
   SELECT
      d.*,
      DATE_FORMAT(d.datum, '%d.%m.%Y') AS 'datum_de',
      IFNULL(e.erkrankung_detail, e.erkrankung) AS 'erkrankung',
      IF(d.bem IS NULL, '--', d.bem) AS 'bem'
   FROM dokument d
      LEFT JOIN erkrankung e  ON e.erkrankung_id = d.erkrankung_id
   WHERE
      d.patient_id = '{$patient_id}' AND
      e.erkrankung IN ('{$rechte}')
   GROUP BY
      d.dokument_id
   ORDER BY d.datum DESC
";

$results = sql_query_array($db, $queryDokument);

$documents = array();

foreach ($results as $result) {
    if (isset($documents[$result['erkrankung']]['content'][$result['datum']]) == false) {
        $documents[$result['erkrankung']]['content'][$result['datum']] = array(
            'date' => $result['datum_de'],
            'content' => array()
        );
    }

    $result['dokument_short'] = substr($result['dokument'], 14);

    $documents[$result['erkrankung']]['content'][$result['datum']]['content'][] = $result;
}

ksort($documents);

//heads zuweisen
foreach ($documents as $erk => $erkContent) {
    $documents[$erk]['value'] = $erkrankungen[$erk];
}

$smarty
    ->assign('documents', $documents)
    ->assign('back_patient', "page=view.patient&amp;patient_id={$patient_id}")
;

?>