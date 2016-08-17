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

switch ($action) {
   case 'insert':
      if (isset($_REQUEST['labor_wert'])) {
         $extValid = $extForm->validateExtFields($_REQUEST['labor_wert'], 'check_labor_werte');
         if ($extValid) {
            $no_error = action_insert( $smarty, $db, $fields, $table, $action);
            if ($no_error) {
               $datum            = reset($fields['datum']['value']);
               $erkrankung_id    = reset($fields['erkrankung_id']['value']);
               $vorlage_labor_id = reset($fields['vorlage_labor_id']['value']);

               $ukey = "erkrankung_id = '{$erkrankung_id}' AND datum = '{$datum}' AND vorlage_labor_id = '{$vorlage_labor_id}'";
               $form_id = dlookup($db, $table, 'MAX(labor_id)', $ukey);
               $extForm->actionInsert(array('labor_id' => $form_id));

               action_cancel( $location );
            }
         }
      } else {
         //wirft in diesem fall immer einen Fehler, aber nicht einfach copy&paste woanders hin!!!
         action_insert( $smarty, $db, $fields, $table, $action);
      }

      break;

   case 'update':
      $extValid = $extForm->validateExtFields($_REQUEST['labor_wert'], 'check_labor_werte');
         if ($extValid) {
            $no_error = action_update( $smarty, $db, $fields, $table, $form_id, $action);
            if ($no_error) {
               $extForm->actionUpdate('labor_wert_id');
               action_cancel( $location );
            }
         }

      break;

   case 'delete':

      $extForm->validateExtFields($_REQUEST['labor_wert']);
      $extForm->actionDelete('labor_id', $form_id);
      action_delete( $smarty, $db, $fields, $table, $form_id, $action, $location );

      break;
   case 'cancel': action_cancel( $location );                                                   break;
}

?>