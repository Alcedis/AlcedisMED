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

       $_REQUEST['user_count'] = 0;
       $no_error = action_insert( $smarty, $db, $fields, $table, $action);

       if ($no_error) {
           $ktpid       = dlookup($db, 'konferenz_teilnehmer_profil', 'MAX(konferenz_teilnehmer_profil_id)', "createuser = '{$user_id}'");
           $location    = get_url("page=list.konferenz_teilnehmer_profil_user&konferenz_teilnehmer_profil_id={$ktpid}");

           action_cancel($location);
       }

       break;
   case 'update':

       $userList = dlookup($db, 'konferenz_teilnehmer_profil', 'user_list', "konferenz_teilnehmer_profil_id = '{$form_id}'");
       $userList = strlen($userList) > 0 ? array_flip(explode(',', $userList)) : array();

       if (isset($_REQUEST['buffer-id']) === true) {

           $buffer = json_decode($_REQUEST['buffer-id'], true);

           if (isset($buffer['add']) === true && count($buffer['add']) > 0) {
               foreach (array_keys($buffer['add']) as $addId) {
                   $userList[$addId] = 1;
               }
           }

           if (isset($buffer['remove']) === true && count($buffer['remove']) > 0) {
               foreach (array_keys($buffer['remove']) as $removeId) {
                   unset($userList[$removeId]);
               }
           }
       }

       $_REQUEST['user_count'] = count($userList);
       $_REQUEST['user_list']  = implode(',', array_keys($userList));

       action_update( $smarty, $db, $fields, $table, $form_id, $action, $location);

       break;

   case 'delete': action_delete( $smarty, $db, $fields, $table, $form_id, $action, $location ); break;
   case 'cancel': action_cancel( $location );                                                   break;
}

?>