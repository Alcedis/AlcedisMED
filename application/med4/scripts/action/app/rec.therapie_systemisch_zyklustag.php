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
      if (isset($_REQUEST['wirkstoff_data'])) {
         $extValid = $extForm->validateExtFields($_REQUEST['wirkstoff_data'], null, 'ext_err_wirkstoff');
         if ($extValid) {
            $no_error = action_insert( $smarty, $db, $fields, $table, $action, '', 'ext_err');
            if ($no_error) {
               $form_id = dlookup($db, $table, 'MAX(therapie_systemisch_zyklustag_id)', "patient_id='$patient_id' AND erkrankung_id='$erkrankung_id'");
               $extForm->actionInsert(array('therapie_systemisch_zyklustag_id' => $form_id));

               action_cancel( $location );
            }
         }
      } else {
         //Hier anzukommen bedeutet eine Dateninkonsistenz in den Vorlagen (Therapievorlage ohne Wirkstoffe!!!)
         //Dummy Action die einen Fehler aufruft
         action_insert( $smarty, $db, $fields, $table, $action, $location, 'ext_err');
      }

      break;

   case 'update':

      if (isset($_REQUEST['wirkstoff_data'])) {
         $extValid = $extForm->validateExtFields($_REQUEST['wirkstoff_data'], null, 'ext_err_wirkstoff');

         if ($extValid) {
            $no_error = action_update( $smarty, $db, $fields, $table, $form_id, $action, null, 'ext_err');

            if ($no_error) {
               $extForm->actionUpdate('therapie_systemisch_zyklustag_wirkstoff_id');

               action_cancel( $location );
            }
         }
      } else {
         //falls jemand nachträglich den therapietag geleert hat und neue vorlagen geladen hat
         //Dummy Action
         action_update( $smarty, $db, $fields, $table, $form_id, $action, null, 'ext_err');
      }

      break;

   case 'delete':

      $extForm->validateExtFields($_REQUEST['wirkstoff_data']);
      $extForm->actionDelete('therapie_systemisch_zyklustag_id', $form_id);
      action_delete( $smarty, $db, $fields, $table, $form_id, $action, $location );

      break;
   case 'cancel': action_cancel( $location );                                                   break;
}

?>