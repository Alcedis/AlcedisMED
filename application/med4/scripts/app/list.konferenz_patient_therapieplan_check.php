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

$loadAgain = false;

if (isset($fields['konferenz_patient_id']['value']) === true)
{
   $datumKonferenz      = null;
   $therapieplanFields  = null;

   foreach ($fields['konferenz_patient_id']['value'] as $index => $konferenzPatientId) {
      $therapieplanId       = $fields['therapieplan_id']['value'][$index];
      $erkrankungId         = $fields['erkrankung_id']['value'][$index];
      $patientId            = $fields['patient_id']['value'][$index];
      $art                  = $fields['art']['value'][$index];

      if (strlen($therapieplanId) > 0) {
         //Therapieplan ist schon vorhanden
         continue;
      }

      //Init
      $loadAgain           = true;
      $datumKonferenz      = $datumKonferenz === null ? dlookup($db, 'konferenz', 'datum', "konferenz_id = '$konferenzId'") : $datumKonferenz;
      $therapieplanFields  = $therapieplanFields === null ? $widget->loadExtFields('fields/app/therapieplan.php') : $therapieplanFields;
      $zeitpunkt           = in_array($art, array('post', 'prae')) === true ? $art : NULL;

      //Gibt es einen Therapieplan am Datum der Konferenz der keinem Protokoll zugewiesen ist
      //dann update diesen!!
      $therapieplanId = dlookup($db, 'therapieplan', 'therapieplan_id', "erkrankung_id = '{$erkrankungId}' AND datum = '{$datumKonferenz}' AND konferenz_patient_id IS NULL");

      $tmpFields = $therapieplanFields;

      if (strlen($therapieplanId) > 0) {
         $dataset = reset(sql_query_array($db, "SELECT * FROM therapieplan WHERE therapieplan_id = '{$therapieplanId}'"));

         $dataset['zeitpunkt']              = $zeitpunkt;
         $dataset['grundlage']              = 'tk';
         $dataset['konferenz_patient_id']   = $konferenzPatientId;

         array2fields($dataset, $tmpFields);

         execute_update($smarty, $db, $tmpFields, 'therapieplan', "therapieplan_id = '{$therapieplanId}'", 'update', "", true);
      } else {

         //Es wurde kein Therapieplan gefunden, also versuche einen anzulegen!
         $newDate = null;
         $interval = 0;

         while ($newDate === null) {
            $result = reset(sql_query_array($db, "
               SELECT
                  DATE_ADD('{$datumKonferenz}', INTERVAL {$interval} DAY) AS 'date',
                  MAX(t.therapieplan_id) AS 'therapieplan_id'
               FROM patient p
                  LEFT JOIN therapieplan t ON t.erkrankung_id = '{$erkrankungId}' AND t.datum = DATE_ADD('{$datumKonferenz}', INTERVAL {$interval} DAY)

               WHERE p.patient_id = '{$patientId}'
            "));

            if (strlen($result['therapieplan_id']) == 0) {
               $newDate = $result['date'];
            } else {
               $interval++;
            }
         }

         //Neuen Therapieplan anlegen
          $dataset = array(
            'patient_id'            => $patientId,
            'erkrankung_id'         => $erkrankungId,
            'datum'                 => $newDate,
            'grundlage'             => 'tk',
            'zeitpunkt'             => $zeitpunkt,
            'konferenz_patient_id'  => $konferenzPatientId
         );

         array2fields($dataset, $tmpFields);

         execute_insert($smarty, $db, $tmpFields, 'therapieplan', 'insert');

         $therapieplanId = dlookup($db, 'therapieplan', 'therapieplan_id', "erkrankung_id = '{$erkrankungId}' AND datum = '{$newDate}'");
      }




      //Status Refresh für Konferenz wegen form_param
      $statusKonferenzPatientId = dlookup($db, 'status', 'status_id', "form = 'konferenz_patient' AND form_id = '{$konferenzPatientId}'");
      $statusTherapieplanId     = dlookup($db, 'status', 'status_id', "form = 'therapieplan' AND form_id = '{$therapieplanId}'");

      //Status von Therapieplan und von konferenz patient updaten
      $statusRefresh = statusRefresh::create($db, $smarty)
         ->setStatusId($statusKonferenzPatientId)
         ->setStatusId($statusTherapieplanId)
         ->refreshStatus()
      ;
   }
}

if ($loadAgain === true) {
   $fields = $backupFields;
   data2list($db, $fields, $sql);
}

?>