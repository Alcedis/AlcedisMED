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
   case "insert":
      //Erkrankung Check (alias Rollen Check)
      if ($_REQUEST['rolle'] === 'admin'
         && ((isset($_SESSION['pos_table']['erkrankung']) === true && count($_SESSION['pos_table']['erkrankung']) > 0) || isset($_REQUEST['recht_global']) == true) ) {
            $smarty->assign('error', $config['msg_no_pos_allowed']);
      } else {
         $no_error = action_insert( $smarty, $db, $fields, "recht", $action, '', 'ext_err');

         if ($no_error){

            $form_id = dlookup($db, $table, 'MAX(recht_id)', "createuser = '$user_id'");

            $fields = $widget->loadExtFields('fields/base/recht_erkrankung.php');
            insert_sess_db($smarty, $db, $fields, 'recht_erkrankung', $form_id, 'erkrankung', 'recht_erkrankung_id', 'recht_id');

            action_cancel($location);
         }
      }

      break;
   case "update":
      //Erkrankung Check (alias Rollen Check)
      if ($_REQUEST['rolle'] === 'admin'
      && ((isset($_SESSION['pos_table']['erkrankung']) === true && count($_SESSION['pos_table']['erkrankung']) > 0) || isset($_REQUEST['recht_global']) == true) ) {
            $smarty->assign('error', $config['msg_no_pos_allowed']);
      } else {
         $no_error = action_update( $smarty, $db, $fields, "recht", $form_id, $action, '', 'ext_err');

         if ($no_error){
            $fields = $widget->loadExtFields('fields/base/recht_erkrankung.php');
            insert_sess_db($smarty, $db, $fields, 'recht_erkrankung', $form_id, 'erkrankung', 'recht_erkrankung_id', 'recht_id');

             action_cancel($location);
         }
      }

      break;
   case "delete":

      $no_error = action_delete( $smarty, $db, $fields, "recht", $form_id, $action);

      if ($no_error) {
         deleteForm($db, $smarty, 'recht_erkrankung', $table, $form_id, 'base');

         action_cancel($location);
      }

      break;
   case "cancel": action_cancel($location); break;
}

?>