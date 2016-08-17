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
   case 'insert':
      $no_error = action_insert($smarty, $db, $fields, $table, $action, null, 'ext_err');

      if ($no_error) {
         $nachsorgeId = dlookup($db, $table, 'MAX(nachsorge_id)', "patient_id = '{$patient_id}' AND createuser = '{$user_id}'");

         $fields = $widget->loadExtFields('fields/app/nachsorge_erkrankung.php');

         $tmpNeFields = $fields;

         //Aktuelle Erkrankung schreiben
         $data = array(
                 'nachsorge_id'          => $nachsorgeId,
                 'patient_id'            => $patient_id,
                 'erkrankung_weitere_id' => $erkrankung_id
         );

         array2fields($data, $tmpNeFields);

         execute_insert($smarty, $db, $tmpNeFields, 'nachsorge_erkrankung', $action, true);

         //Erweiterte Erkrankungen schreiben
         insert_sess_db($smarty, $db, $fields, 'nachsorge_erkrankung', $nachsorgeId, 'erkrankung', 'nachsorge_erkrankung_id', 'nachsorge_id');

         action_cancel( $location );
      }

      break;

   case 'update':
      $no_error = action_update( $smarty, $db, $fields, $table, $form_id, $action, null, 'ext_err');

      if ($no_error) {
         $fields = $widget->loadExtFields('fields/app/nachsorge_erkrankung.php');
         insert_sess_db($smarty, $db, $fields, 'nachsorge_erkrankung', $form_id, 'erkrankung', 'nachsorge_erkrankung_id', 'nachsorge_id');

         action_cancel( $location );
      }

      break;

   case 'delete':
      $no_error = action_delete($smarty, $db, $fields, $table, $form_id, $action);

      if ($no_error) {
         deleteForm($db, $smarty, 'nachsorge_erkrankung', 'nachsorge', $form_id);
         action_cancel($location);
      }

      break;

   case 'cancel': action_cancel($location); break;
}

?>