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

switch( $action )
{
    case 'add':
      //Patient der Konferenz zuordnen
       if (isset($_REQUEST['buffer-id']) === true) {

          $buffer = json_decode($_REQUEST['buffer-id'], true);
          $buffer = is_array($buffer) === true ? $buffer : array('add' => array(),'remove' => array());
          $add    = array_key_exists('add', $buffer) === true       ? implode(',', array_keys($buffer['add']))      : '';
          $remove = array_key_exists('remove', $buffer) === true    ? implode(',', array_keys($buffer['remove']))   : '';

          //Add konferenz patient
          if (strlen($add) > 0) {

              $statusRefresh = statusRefresh::create($db, $smarty);

              $fields = $widget->loadExtFieldsOnce('fields/app/konferenz_patient.php');

              $refresh = false;
              $errors  = array();

              $datumKonferenz = dlookup($db, 'konferenz', 'datum', "konferenz_id = '{$konferenz_id}'");

              $result = sql_query_array($db, "
                SELECT
                   kp.*,
                   IF(MAX(k.konferenz_id) IS NOT NULL, 1, 0) AS 'alreadyinconference',
                   MAX(k.bez)  AS 'konferenz',
                   DATE_FORMAT(MAX(k.datum), '%d.%m.%Y') AS 'konferenz_datum',
                   CONCAT_WS(', ', p.nachname, p.vorname) AS 'patient',
                   lart.bez AS 'kart',
                   erk.bez AS 'erkrankung'
                FROM konferenz_patient kp
                   LEFT JOIN konferenz_patient kpae ON kpae.erkrankung_id = kp.erkrankung_id
                      LEFT JOIN konferenz k ON kpae.konferenz_id = k.konferenz_id AND k.datum = '{$datumKonferenz}'

                   LEFT JOIN patient p ON kp.patient_id = p.patient_id
                   LEFT JOIN l_basic lart ON lart.klasse = 'tumorkonferenz_art' AND lart.code = kp.art
                   LEFT JOIN erkrankung e ON kp.erkrankung_id = e.erkrankung_id
                     LEFT JOIN l_basic erk ON erk.klasse = 'erkrankung' AND erk.code = e.erkrankung

                WHERE
                  kp.konferenz_patient_id IN ($add)
                GROUP BY
                  kp.konferenz_patient_id
              ");

              foreach ($result AS $kp) {

                 if ($kp['alreadyinconference'] == 0) {
                    $tmpFields = $fields;

                    $kpId = $kp['konferenz_patient_id'];

                    $kp['konferenz_id'] = $konferenz_id;
                    array2fields($kp, $tmpFields);

                    execute_update($smarty, $db, $tmpFields, 'konferenz_patient', "konferenz_patient_id = '{$kpId}'", 'update', "", true);

                    $statusRefresh
                       ->setStatusId(dlookup($db, 'status', 'status_id', "form = 'konferenz_patient' AND form_id = '{$kpId}'"))
                    ;

                    //Set Dirty State to Protocol
                    protocol::create($db, $smarty, false)
                       ->makeDirty(protocol::$kp, $kpId)
                    ;

                    $refresh = true;
                 } else {
                    $errors[] = sprintf($config['msg_alreadyinconference'], $kp['patient'], $kp['erkrankung'], $kp['konferenz_datum'], $kp['konferenz']);
                 }
              }

              if (count($errors) > 0) {
                 $_SESSION['sess_error'] = implode('<br/>', $errors);
              }

              //only refresh if min one is updated
              if ($refresh === true) {
                 $statusRefresh
                    ->refreshStatus(true, false, true)
                 ;
              }
           }
       }

      action_cancel( $location );

      break;


   case 'cancel':

      action_cancel( $location );

      break;
}

?>