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
      $no_error = action_insert( $smarty, $db, $fields, $table, $action, '', 'ext_err');

      if ($no_error) {
         $anamneseId = dlookup($db, $table, 'MAX(anamnese_id)', "createuser = '$user_id'");

         $fields = $widget->loadExtFields('fields/app/anamnese_familie.php');
         insert_sess_db($smarty, $db, $fields, 'anamnese_familie', $anamneseId, 'familie', 'anamnese_familie_id', 'anamnese_id');

         $fields = $widget->loadExtFields('fields/app/anamnese_erkrankung.php');
         insert_sess_db($smarty, $db, $fields, 'anamnese_erkrankung', $anamneseId, 'erkrankung', 'anamnese_erkrankung_id', 'anamnese_id');

         action_cancel($location);

      }
      break;
   case 'update':
      $no_error = action_update( $smarty, $db, $fields, $table, $form_id, $action,'', 'ext_err');

      if ($no_error) {
         $fields = $widget->loadExtFields('fields/app/anamnese_familie.php');
         insert_sess_db($smarty, $db, $fields, 'anamnese_familie', $form_id, 'familie', 'anamnese_familie_id', 'anamnese_id');

         $fields = $widget->loadExtFields('fields/app/anamnese_erkrankung.php');
         insert_sess_db($smarty, $db, $fields, 'anamnese_erkrankung', $form_id, 'erkrankung', 'anamnese_erkrankung_id', 'anamnese_id');

         action_cancel($location);
      }

      break;
   case 'delete':

      $no_error = action_delete( $smarty, $db, $fields, $table, $form_id, $action);

      if ($no_error) {
         deleteForm($db, $smarty, 'anamnese_erkrankung', $table, $form_id);
         deleteForm($db, $smarty, 'anamnese_familie', $table, $form_id);

         action_cancel( $location );
      }

      break;
   case 'cancel': action_cancel( $location );                                                   break;
}

?>