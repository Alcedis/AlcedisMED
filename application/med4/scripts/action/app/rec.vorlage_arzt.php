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

         $location   = get_url("page=rec.vorlage_arzt&arzt_id={$form_id}");

         unset($dataset['reset_cookie']);

         execute_update( $smarty, $db, dataArray2fields($dataset, $fields), $table, "user_id = '$form_id'", 'update');

         action_cancel($location);
      }

      break;

   case 'inactive':

      if (isset($_REQUEST['inaktiv']) == true) {
         $dataset = end(sql_query_array($db, "SELECT * FROM user WHERE user_id = '$form_id'"));
         $dataset['inaktiv'] = 1;

         $location   = get_url("page=rec.vorlage_arzt&arzt_id={$form_id}");

         unset($dataset['reset_cookie']);

         execute_update( $smarty, $db, dataArray2fields($dataset, $fields), $table, "user_id = '$form_id'", 'update');

         action_cancel($location);
      }

      break;

   case 'insert':
      $_REQUEST['loginname'] = time() . $user_id;
      $_REQUEST['candidate'] = 1;
      $_REQUEST['pwd'] = md5(1);

      action_insert( $smarty, $db, $fields, $table, $action, $location);

      break;

   case 'update':
       // take original data
       $dataset = end(sql_query_array($db, "SELECT * FROM user WHERE user_id = '$form_id'"));

       $_REQUEST['loginname']  = $dataset['loginname'];
       $_REQUEST['pwd']        = $dataset['pwd'];
       $_REQUEST['pwd_change'] = $dataset['pwd_change'];

      action_update( $smarty, $db, $fields, $table, $form_id, $action, $location);

      break;

   case 'delete': action_delete( $smarty, $db, $fields, $table, $form_id, $action, $location ); break;
   case 'cancel': action_cancel( $location ); break;
}

?>
