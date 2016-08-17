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

$queryFoto = "
   SELECT
      f.*,
      DATE_FORMAT(f.datum, '%d.%m.%Y') AS 'datum_de',
      IFNULL(e.erkrankung_detail, e.erkrankung) AS 'erkrankung'
   FROM foto f
      LEFT JOIN erkrankung e  ON e.erkrankung_id = f.erkrankung_id
   WHERE
      f.patient_id = '{$patient_id}' AND
      e.erkrankung IN ('{$rechte}')
   GROUP BY
      f.foto_id
   ORDER BY f.datum DESC
";

$results = sql_query_array($db, $queryFoto);

$images = array();

foreach ($results as $result) {
    if (isset($images[$result['erkrankung']]['content'][$result['datum']]) == false) {
        $images[$result['erkrankung']]['content'][$result['datum']] = array(
            'date' => $result['datum_de'],
            'content' => array()
        );
    }

    $images[$result['erkrankung']]['content'][$result['datum']]['content'][] = $result;
}

ksort($images);

//heads zuweisen
foreach ($images as $erk => $erkContent) {
    $images[$erk]['value'] = $erkrankungen[$erk];
}

$smarty
    ->assign('images', $images)
    ->assign('back_patient', "page=view.patient&amp;patient_id={$patient_id}")
;

?>