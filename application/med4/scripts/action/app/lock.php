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

$location = get_url('list.patient');

if ($patient_id !== null) {
   $location = get_url("page=view.patient&patient_id=$patientId");
}

if ($erkrankung_id !== null) {
   $location = get_url("page=view.erkrankung&erkrankung_id=$erkrankung_id");
}

switch( $action )
{
   case 'status':
      $time       = date('Y-m-d H:m:s');
      $lock       = isset($_REQUEST['lock']) === true    ? $_REQUEST['lock']     : null;
      $unlock     = isset($_REQUEST['unlock']) === true  ? $_REQUEST['unlock']   : null;
      $toLock     = null;
      $toUnlock   = null;

      $status_lock_bem_id = 'null';

      if ($lock !== null || $unlock !== null) {
         //Bemerkung
         if (strlen($_REQUEST['bem'])) {
            $bem = $_REQUEST['bem'];

            $query = "
               INSERT INTO
                  status_lock_bem ( user_id, bem )
               VALUES (
                  $user_id, '$bem'
            )";

            mysql_query($query, $db);

            $status_lock_bem_id = dlookup($db, 'status_lock_bem', 'MAX(status_lock_bem_id)', "user_id = $user_id");
         }
      }

      //Wenn mindestens einer gesperrt werden soll
      if ($lock !== null) {
         $toLock     = array_keys($lock);
         $toLockImp  = implode(",", $toLock);

         //Status ändern
         $query = "
            UPDATE
               status
            SET
               form_status = IF(form_status < 3, 3, form_status),
               status_lock = 1
            WHERE
               status_id IN ($toLockImp)
         ";

         mysql_query($query, $db);

         //Status lock log
         foreach ($toLock as $lockId) {
            $query = "
               INSERT INTO status_lock
                  (status_id, `lock`, status_lock_bem_id, user_id, `time`)
               VALUES (
                  $lockId, 1, $status_lock_bem_id, $user_id, '$time'
               )";

            mysql_query($query, $db);
         }
      }

      if ($unlock !== null) {

         $toUnlock      = array_keys($unlock);
         $toUnlockImp   = implode(",", $toUnlock);

         //Status ändern
         $query = "
            UPDATE
               status
            SET
               status_lock = 0
            WHERE
               status_id IN ($toUnlockImp)
         ";

         mysql_query($query, $db);

         $statusRefresh = statusRefresh::create($db, $smarty)
            ->setStatusIds($toUnlock)
            ->refreshStatus(false, true)
         ;

         //Status unlock log
         foreach ($toUnlock as $unlockId) {
            $query = "
               INSERT INTO status_lock
                  (status_id, `lock`, status_lock_bem_id, user_id, `time`)
               VALUES (
                  $unlockId, 0, $status_lock_bem_id, $user_id, '$time'
               )";

           mysql_query($query, $db);
         }
      }

      if ($toLock !== null || $toUnlock !== null) {
         $statusRefresh = statusRefresh::create($db, $smarty)
            ->setStatusIds($toLock)
            ->setStatusIds($toUnlock)
            ->refreshDisease();
      }

      action_cancel( $location );

      break;

   case 'cancel':
      action_cancel( $location );

      break;
}

?>