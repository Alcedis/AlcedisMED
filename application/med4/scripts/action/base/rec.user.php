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
   case 'active':

      if (isset($_REQUEST['inaktiv']) == false) {
         $dataset = end(sql_query_array($db, "SELECT * FROM user WHERE user_id = '$form_id'"));
         unset($dataset['inaktiv']);

         $location   = get_url("page=rec.user&user_id={$form_id}");

         unset($dataset['reset_cookie']);

         execute_update( $smarty, $db, dataArray2fields($dataset, $fields), 'user', "user_id = '$form_id'", 'update');

         action_cancel($location);
      }

      break;

   case 'inactive':

      if (isset($_REQUEST['inaktiv']) == true) {
         $dataset = end(sql_query_array($db, "SELECT * FROM user WHERE user_id = '$form_id'"));
         $dataset['inaktiv'] = 1;

         $location   = get_url("page=rec.user&user_id={$form_id}");

         unset($dataset['reset_cookie']);

         execute_update( $smarty, $db, dataArray2fields($dataset, $fields), 'user', "user_id = '$form_id'", 'update');

         action_cancel($location);
      }

      break;

   case 'insert': action_insert( $smarty, $db, $fields, $table, $action, $location, 'ext_err'); break;
   case 'update': action_update( $smarty, $db, $fields, $table, $form_id, $action, $location, 'ext_err');  break;
   case 'delete':

      $no_error = action_delete( $smarty, $db, $fields, $table, $form_id, $action);

      if ($no_error) {
         $query = "SELECT * FROM recht WHERE user_id = '$form_id'";

         foreach (sql_query_array($db, $query) as $recht) {
            $rechtId = $recht['recht_id'];

            deleteForm($db, $smarty, 'recht_erkrankung', 'recht', $rechtId, 'base');
         }

         deleteForm($db, $smarty, 'recht', 'user', $form_id, 'base');

         action_cancel( $location );
      }
      break;
   case 'cancel': action_cancel( $location );                                                   break;
}

?>