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

function syncSynchronousDiseases(smarty $smarty, $db, $patientId)
{
    // create synchronous diseases
    $query = "SELECT * FROM erkrankung_synchron WHERE patient_id = '{$patientId}'";

    $fields = $smarty->widget->loadExtFields('fields/app/erkrankung_synchron.php');

    foreach (sql_query_array($db, $query) as $se) {
        $diseaseId = $se['erkrankung_id'];
        $esId      = $se['erkrankung_synchron'];

        $counterpartExist = dlookup($db, 'erkrankung_synchron', 'erkrankung_synchron_id', "erkrankung_id = '{$esId}' AND erkrankung_synchron = '{$diseaseId}'");

        if (strlen($counterpartExist) == 0) {
            $tmpFields = $fields;

            array2fields(array(
                'patient_id'          => $patientId,
                'erkrankung_id'       => $esId,
                'erkrankung_synchron' => $diseaseId
            ), $tmpFields);

            execute_insert($smarty, $db, $tmpFields, 'erkrankung_synchron', 'insert', true);
        }
    }
}


$query = "
    UPDATE patient p
        INNER JOIN (
            SELECT
               GROUP_CONCAT(DISTINCT l.bez ORDER BY l.bez ASC SEPARATOR ', ') as erkrankungen
            FROM erkrankung e
                LEFT JOIN l_basic l ON l.klasse = IF(e.erkrankung_detail IS NOT NULL, 'erkrankung_sst_detail', 'erkrankung') AND
                                    l.code = IF(e.erkrankung_detail IS NOT NULL, e.erkrankung_detail, e.erkrankung)
            WHERE e.patient_id = '{$patient_id}'
        ) x
    SET
        p.erkrankungen = x.erkrankungen
    WHERE
        p.patient_id = '{$patient_id}'
";

switch( $action )
{
    case 'insert':
    case 'ekr':
        $no_error = action_insert($smarty, $db, $fields, $table, 'insert', '', 'ext_err');

        if ($no_error) {
            $erkrankung_id = dlookup($db, 'erkrankung', 'MAX(erkrankung_id)', "patient_id='$patient_id'");

            //Update patient.erkrankungen
            mysql_query($query, $db);

            if ($action == 'ekr') {
                $location = get_url('page=rec.ekr') . "&erkrankung_id=$erkrankung_id&patient_id=$patient_id";
            } else {
                $location .= "&erkrankung_id=$erkrankung_id";
             }

            $fields = $widget->loadExtFields('fields/app/erkrankung_synchron.php');
            insert_sess_db($smarty, $db, $fields, 'erkrankung_synchron', $erkrankung_id, 'synchron', 'erkrankung_synchron_id', 'erkrankung_id');

            syncSynchronousDiseases($smarty, $db, $patient_id);

            action_cancel( $location );
        }

        break;

    case 'update':
        $no_error = action_update($smarty, $db, $fields, $table, $form_id, $action, '', 'ext_err');

        if ($no_error) {
            //Update patient.erkrankungen
            mysql_query($query, $db);

            //Erkrankung sst extension
            if (isset($fields['erkrankung']['value'][0]) === true && reset($fields['erkrankung']['value']) == 'sst') {
                $erkrankung = isset($fields['erkrankung_detail']['value'][0]) && strlen($fields['erkrankung_detail']['value'][0]) > 0
                    ? dlookup($db, 'l_basic', 'bez', "klasse = 'erkrankung_sst_detail' AND code = '{$fields['erkrankung_detail']['value'][0]}'")
                    : dlookup($db, 'l_basic', 'bez', "klasse = 'erkrankung' AND code = 'sst'")
                ;

                $_SESSION['sess_erkrankung_data']['bez'] = $erkrankung;
            }

            $location .= "&erkrankung_id={$form_id}";

            $fields = $widget->loadExtFields('fields/app/erkrankung_synchron.php');
            insert_sess_db($smarty, $db, $fields, 'erkrankung_synchron', $form_id, 'synchron', 'erkrankung_synchron_id', 'erkrankung_id');

            syncSynchronousDiseases($smarty, $db, $patient_id);

            action_cancel($location);
        }

        break;

   case 'delete':
      $tables = array_unique(array_merge($erkrankung_tables, $dlist_tables));

      $no_error = deleteDisease($db, $smarty, $tables, $form_id);

      if ($no_error) {
          //Update patient.erkrankungen
          mysql_query($query, $db);

          deleteForm($db, $smarty, 'erkrankung_synchron', $table, $form_id);
      }

      $location = get_url('page=view.patient') . "&patient_id={$patient_id}";

      action_cancel($location);

      break;

   case 'cancel':
      $location .= "&erkrankung_id=$form_id";
      if (!strlen($form_id))
         $location   = get_url('page=view.patient') . "&patient_id=$patient_id";

      action_cancel( $location );

      break;
}

?>
