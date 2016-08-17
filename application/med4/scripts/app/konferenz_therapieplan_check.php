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

$query = "
   SELECT
      kp.*,
      th.therapieplan_id,
      th.createtime as therapieplan_createtime
   FROM konferenz_patient kp
      LEFT JOIN therapieplan th ON th.konferenz_patient_id = kp.konferenz_patient_id
   WHERE kp.konferenz_id = '$konferenzId'
   ORDER BY kp.konferenz_patient_id
";

$result = sql_query_array($db, $query);

foreach ($result AS $dataset) {
   $therapieplanId      = $dataset['therapieplan_id'];
   $erkrankungId        = $dataset['erkrankung_id'];
   $patientId           = $dataset['patient_id'];
   $art                 = $dataset['art'];
   $konferenzPatientId  = $dataset['konferenz_patient_id'];
   $datumKonferenz      = dlookup($db, 'konferenz', 'datum', "konferenz_id = '$konferenzId'");

   //Wenn kein therapieplan zugewiesen wurde, aber ein therapieplan mit dem datum der konferenz schon existiert
   if (strlen($therapieplanId) == 0) {
      $therapieplanId = dlookup($db, 'therapieplan', 'therapieplan_id', "erkrankung_id = '$erkrankungId' AND datum = '$datumKonferenz'");
   }

   //Update
   if (strlen($therapieplanId) > 0) {

      $zeitpunkt = in_array($art, array('post', 'prae')) === true ? "'$art'" : "NULL";

      $query = "
         UPDATE
            therapieplan
         SET
            grundlage = 'tk',
            zeitpunkt = $zeitpunkt,
            konferenz_patient_id = '$konferenzPatientId'
         WHERE
            therapieplan_id = '$therapieplanId'
      ";

      mysql_query( $query, $db );

   } else {

      $therapieplanFields = $widget->loadExtFields('fields/app/therapieplan.php');

      $zeitpunkt = in_array($art, array('post', 'prae')) === true ? $art : '';

      $dataset = array(
         'patient_id'            => $patientId,
         'erkrankung_id'         => $erkrankungId,
         'datum'                 => $datumKonferenz,
         'grundlage'             => 'tk',
         'zeitpunkt'             => $zeitpunkt,
         'konferenz_patient_id'  => $konferenzPatientId
      );

      array2fields($dataset, $therapieplanFields);

      execute_insert( $smarty, $db, $therapieplanFields, 'therapieplan', 'insert');

      //Status von Therapieplan und von konferenz patient updaten
      $therapieplanId = dlookup($db, 'therapieplan', 'therapieplan_id', "erkrankung_id = '$erkrankungId' AND datum = '$datumKonferenz'");
   }

    $statusKonferenzPatientId = dlookup($db, 'status', 'status_id', "form = 'konferenz_patient' AND form_id = '{$konferenzPatientId}'");
    $statusTherapieplanId     = dlookup($db, 'status', 'status_id', "form = 'therapieplan' AND form_id = '{$therapieplanId}'");

    $statusRefresh = statusRefresh::create($db, $smarty)
        ->setStatusId($statusKonferenzPatientId)
        ->setStatusId($statusTherapieplanId)
        ->refreshStatus()
    ;
}

?>