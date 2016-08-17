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
      $no_error =  action_insert( $smarty, $db, $fields, $table, $action, '', 'ext_err');

      if ($no_error) {
         $form_id = dlookup($db, $table, 'MAX(untersuchung_id)', "createuser = '$user_id'");

         $fields = $widget->loadExtFields('fields/app/untersuchung_lokalisation.php');
         insert_sess_db($smarty, $db, $fields, 'untersuchung_lokalisation', $form_id, 'lokalisation', 'untersuchung_lokalisation_id', 'untersuchung_id');

         action_cancel( $location );
      }
      break;
   case 'update':
      $no_error = action_update( $smarty, $db, $fields, $table, $form_id, $action, '', 'ext_err');

      if ($no_error) {
         $fields = $widget->loadExtFields('fields/app/untersuchung_lokalisation.php');
         insert_sess_db($smarty, $db, $fields, 'untersuchung_lokalisation', $form_id, 'lokalisation', 'untersuchung_lokalisation_id', 'untersuchung_id');

         action_cancel( $location );
      }
      break;
   case 'delete':

      $no_error = action_delete( $smarty, $db, $fields, $table, $form_id, $action);

      if ($no_error) {
         deleteReference($db, 'diagnose', $table, $form_id);
         deleteReference($db, 'komplikation', $table, $form_id);

         deleteForm($db, $smarty, 'untersuchung_lokalisation', $table, $form_id);

         action_cancel( $location );
      }

      break;

   case 'cancel': action_cancel( $location );                                                   break;
}

?>