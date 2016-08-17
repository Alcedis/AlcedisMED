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

switch ($action)
{
    case 'insert':
        if (appSettings::get('auto_patient_id') === true) {
            $patientOrgId = isset($_REQUEST['org_id']) === true && strlen($_REQUEST['org_id']) ? $_REQUEST['org_id'] : null;

            $validOrgId = strlen(dlookup($db, 'org', 'org_id', "org_id = '{$patientOrgId}'")) > 0;

            if ($patientOrgId !== null && $validOrgId === true) {

                $maxPatNo = dlookup($db, 'patient', 'MAX(patient_nr)', "org_id = '{$patientOrgId}'");

                if ($maxPatNo !== null) {
                    $maxPatNo = substr($maxPatNo, 4);
                    $maxPatNo++;
                } else {
                    $maxPatNo = 1;
                }

                $patId = array(
                        str_pad($patientOrgId, 3, '0', STR_PAD_LEFT),
                        str_pad($maxPatNo, 5, '0', STR_PAD_LEFT)
                );

                $_REQUEST['patient_nr'] = concat($patId, '-');
          }
      }

      $no_error = action_insert($smarty, $db, $fields, $table, $action, '', 'ext_err');

      if ($no_error) {
         $patientOrgId = reset($fields['org_id']['value']);
         $patient_id = dlookup($db, 'patient', 'MAX(patient_id)', "org_id='{$patientOrgId}'");
         $location = get_url('page=view.patient') . "&patient_id={$patient_id}";
         action_cancel($location);
      }

      break;

   case 'update':
      $no_error = action_update( $smarty, $db, $fields, $table, $form_id, $action, '', 'ext_err');

      if ($no_error) {
         $location = isset($backPage) == true ? get_url($backPage) : get_url('page=view.patient') . "&patient_id={$form_id}";

         //sess patient data reload
         unset($_SESSION['sess_patient_data']);

         // echeck 5 - geschlechtfeld bei brust
         $results = sql_query_array($db, "
            SELECT s.status_id
            FROM status s
               INNER JOIN erkrankung e ON s.form_id = e.erkrankung_id AND e.erkrankung = 'b'
            WHERE
               s.patient_id = '{$form_id}'
               AND s.form = 'erkrankung'
         ");

         $refreshIds = array();

         foreach ($results as $result) {
            $refreshIds[] = $result['status_id'];
         }

         if (count($refreshIds) > 0) {
            //Status Refresh für eCheck 5 der formübergreifenden Regeln
            $statusRefresh = statusRefresh::create($db, $smarty);

            foreach ($refreshIds as $id) {
               $statusRefresh->setStatusId($id);
            }

            $statusRefresh->refreshStatus();
         }

         action_cancel( $location );
      }

      break;

   case 'delete':

      require 'core/functions/patient.php';

      $tables = array_unique(array_merge($patient_tables, $dlist_tables, $erkrankung_tables));

      deletePatient($db, $smarty, $tables, $form_id);

      action_cancel(get_url('page=list.patient'));

      break;

   case 'cancel':

      $location = isset($backPage) == true ? get_url($backPage) : get_url('page=view.patient') . "&patient_id={$form_id}";

      if (!strlen($form_id)) {
         $location   = isset($_REQUEST['no_import']) ? get_url('page=list.patient') : get_url('page=list.patient_import');
      }

      action_cancel($location);

      break;
}

?>
