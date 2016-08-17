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

$time = date('Y-m-d H:i');

$id      = isset($_REQUEST['action'][$action]) === true ? key($_REQUEST['action'][$action]) : null;
$isHl7   = strpos($id, 'hl7') === false ? false : true;

if ($id !== null) {

    $preSelectedErkrankung = isset($_REQUEST['erkrankung'][$id]) === true ? $_REQUEST['erkrankung'][$id] : '';

    switch ($action) {

        case 'import':

            if ($isHl7 === false) {
                if (strlen($preSelectedErkrankung) == 0) {
                    action_cancel(get_url("page=rec.erkrankung&patient_id={$id}"));
                } else {

                    $fields = $widget->loadExtFields('fields/app/erkrankung.php');

                     $data = array(
                          'patient_id'   => $id,
                          'erkrankung'   => $preSelectedErkrankung,
                          'createuser'   => $user_id,
                          'createtime'   => $time
                      );

                      array2fields($data, $fields);

                      execute_insert( $smarty, $db, $fields, 'erkrankung', 'insert');

                      mysql_query("
                         UPDATE patient d
                            INNER JOIN (
                               SELECT x.patient_id, GROUP_CONCAT(DISTINCT l.bez SEPARATOR ', ') as erk
                                  FROM patient x
                                  INNER JOIN erkrankung e ON e.patient_id = x.patient_id
                                  LEFT JOIN l_basic l ON l.klasse = 'erkrankung' AND l.code = e.erkrankung
                                  WHERE x.patient_id = '{$id}'
                                  GROUP BY x.patient_id
                               ) u ON u.patient_id = d.patient_id

                         SET d.erkrankungen = u.erk
                         WHERE d.patient_id = '{$id}'
                      ", $db);

                      $newDiseaseId = dlookup($db, 'erkrankung', 'MAX(erkrankung_id)', "patient_id = '{$id}' AND erkrankung = '{$preSelectedErkrankung}'");

                      action_cancel(get_url("page=rec.erkrankung&erkrankung_id={$newDiseaseId}"));
                }
            } else {
                $hl7Main = hl7Main::getInstance()
                    ->importCacheId(substr($id, 4), $preSelectedErkrankung)
                ;

                if ($hl7Main->getDiseaseId() !== null) {
                    action_cancel(get_url("page=rec.erkrankung") . "&erkrankung_id={$hl7Main->getDiseaseId()}");
                } elseif ($hl7Main->getPatientId() !== null) {
                    action_cancel(get_url("page=rec.erkrankung") . "&patient_id={$hl7Main->getPatientId()}");
                }
            }

      break;

   case 'patient':

         //Is HL7 Patient
         if (strlen($preSelectedErkrankung)) {

            switch ($isHl7) {

               case false:

                  $fields = $widget->loadExtFields('fields/app/erkrankung.php');

                  $data = array(
                      'patient_id'   => $id,
                      'erkrankung'   => $preSelectedErkrankung,
                      'createuser'   => $user_id,
                      'createtime'   => $time
                  );

                  array2fields($data, $fields);

                  execute_insert( $smarty, $db, $fields, 'erkrankung', 'insert');

                  mysql_query("
                     UPDATE patient d
                        INNER JOIN (
                           SELECT x.patient_id, GROUP_CONCAT(DISTINCT l.bez SEPARATOR ', ') as erk
                              FROM patient x
                              INNER JOIN erkrankung e ON e.patient_id = x.patient_id
                              LEFT JOIN l_basic l ON l.klasse = 'erkrankung' AND l.code = e.erkrankung
                              WHERE x.patient_id = '{$id}'
                              GROUP BY x.patient_id
                           ) u ON u.patient_id = d.patient_id

                     SET d.erkrankungen = u.erk
                     WHERE d.patient_id = '{$id}'
                  ", $db);

                  action_cancel(get_url("page=rec.patient&patient_id={$id}"));

                  break;

               case true:
                  $patientId = hl7Main::getInstance()
                       ->importCacheId(substr($id, 4), $preSelectedErkrankung)
                       ->getPatientId()
                  ;

                  if ($patientId !== null) {
                     action_cancel(get_url("page=rec.patient") . "&patient_id={$patientId}");
                  }

                  break;
            }
         } else {
            $smarty
               ->assign("error", $config['valid_field'])
               ->assign("patient_error", array($id));
         }

        break;
    }
}

?>