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

$rechte = isset($_SESSION['sess_recht_erkrankung']) === true ? implode("','", $_SESSION['sess_recht_erkrankung']) : '';

$queryLock = "
   SELECT
      f.*,
      fe.erkrankung
   FROM foto f
      LEFT JOIN patient p ON f.patient_id = f.patient_id

      LEFT JOIN erkrankung fe ON f.erkrankung_id = fe.erkrankung_id

      LEFT JOIN erkrankung e  ON e.patient_id = f.patient_id
   WHERE
      f.patient_id = '$patient_id' AND
      p.org_id = '{$org_id}' AND
      e.erkrankung IN ('{$rechte}')
   GROUP BY
      f.foto_id
   ORDER BY e.erkrankung
";

$resultLock = sql_query_array($db, $queryLock);


$_SESSION['origin'] = array(
   'patient_id'      => $patient_id,
   'page'            => 'foto_patient'
);

$fields = array(
    'foto_id'       => array('type' => 'int'),
    'erkrankung_id' => array('type' => 'int'),
    'patient_id'    => array('type' => 'int'),
    'foto'          => array('type' => 'string'),
    'bez'           => array('type' => 'string'),
    'img_type'      => array('type' => 'string'),
    'erkrankung'    => array('type' => 'string')
);

$fotoErkrankung = array();
$erkQuery = sql_query_array($db, "SELECT code, bez FROM l_basic WHERE klasse='erkrankung'");
foreach ($erkQuery as $erkData) {
    $fotoErkrankung[$erkData['code']] = $erkData['bez'];
}

$sql = get_sql('list', $queryLock, $where, $order, $limit);
data2list($db, $fields, $sql);

$smarty
    ->assign('erkArr', $fotoErkrankung)
    ->assign('back_btn', "page=view.patient&amp;patient_id=$patient_id");

if (!isset($fields['erkrankung']['value']))
    $smarty->assign('message', $config['msg_no_images']);

?>