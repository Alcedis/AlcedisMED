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

$time    = date('Y-m-d H:m:s');

switch( $action )
{
   case 'reopen':
      $konferenzId = isset($_REQUEST['konferenz_id']) === true ? $_REQUEST['konferenz_id'] : null;

      if ($konferenzId !== null) {

         //TUK Öffnen
         $query = "UPDATE konferenz SET final = null WHERE konferenz_id = '$konferenzId'";
         mysql_query($query, $db);

         $konferenzName = dlookup($db, 'konferenz', 'bez', "konferenz_id = '$konferenzId'");
         $bem           = sprintf($config['lbl_oeffnen_konferenz'], $konferenzName);

         $query = "
            SELECT
               s.status_id AS kp_status_id,
               th_s.status_id AS th_status_id
            FROM konferenz_patient kp
               INNER JOIN `status` s      ON s.form = 'konferenz_patient' AND s.form_id = kp.konferenz_patient_id
               LEFT JOIN therapieplan th  ON th.konferenz_patient_id = kp.konferenz_patient_id
               LEFT JOIN `status` th_s    ON th_s.form = 'therapieplan' AND th_s.form_id = th.therapieplan_id
            WHERE
               kp.konferenz_id = '{$konferenzId}'
         ";

         $result = sql_query_array($db, $query);

         if (count($result)) {
            $query = "
               INSERT INTO
                  status_lock_bem ( user_id, bem )
               VALUES (
                  {$user_id}, '{$bem}'
            )";

            mysql_query($query, $db);

            $statusLockBemId = dlookup($db, 'status_lock_bem', 'MAX(status_lock_bem_id)', "user_id = '{$user_id}'");
         }

         $statusIds = array();

         foreach ($result as $dataset) {

            $query = "
               UPDATE `status`
               SET
                  status_lock = 0
               WHERE
                  status_id = '{$dataset['kp_status_id']}'
            ";

            mysql_query($query, $db);

            $query = "
               INSERT INTO `status_lock` (status_id, `lock`, status_lock_bem_id, user_id, `time`)
               VALUES ({$dataset['kp_status_id']}, 0, {$statusLockBemId}, {$user_id}, '{$time}')";

            mysql_query($query, $db);

            $statusIds[] = $dataset['kp_status_id'];

            if (strlen($dataset['th_status_id'])) {
               $query = "
                  UPDATE `status`
                  SET
                     status_lock = 0
                  WHERE
                     status_id = '{$dataset['th_status_id']}'
               ";

               mysql_query($query, $db);

               $query = "
                  INSERT INTO `status_lock` (status_id, `lock`, status_lock_bem_id, user_id, `time`)
                  VALUES ({$dataset['th_status_id']}, 0, {$statusLockBemId}, {$user_id}, '{$time}')";

               mysql_query($query, $db);

               $statusIds[] = $dataset['th_status_id'];
            }
         }

         if (count($statusIds) > 0) {
            $statusRefresh = statusRefresh::create($db, $smarty)
               ->setStatusIds($statusIds)
               ->refreshStatus()
            ;
         }

         action_cancel( $location );
      }

      break;

   case 'insert':
   case 'update':

      if ($action == 'insert') {
         $no_error = action_insert( $smarty, $db, $fields, $table, $action, '', 'ext_err', 'ext_warn');
      } else {
         $no_error = action_update( $smarty, $db, $fields, $table, $form_id, $action, '', 'ext_err', 'ext_warn');

         if ($no_error) {
             protocol::create($db, null, false)->conferenceDetachedConvert($form_id);
         }
      }

      if (isset($_REQUEST['final']) == true && $no_error) {

         //Status Aktualisieren
         $statusRefresh = statusRefresh::create($db, $smarty);

         //Status ändern
         $konferenzName = reset($fields['bez']['value']);
         $bem           = sprintf($config['lbl_abschluss_konferenz'], $konferenzName);

         $query = "
            SELECT
               s.status_id       AS kp_status_id,
               th_s.status_id    AS th_status_id
            FROM konferenz_patient kp
               INNER JOIN `status` s ON s.form = 'konferenz_patient' AND s.form_id = kp.konferenz_patient_id
               LEFT JOIN therapieplan th ON th.konferenz_patient_id = kp.konferenz_patient_id
                  LEFT JOIN `status` th_s ON th_s.form = 'therapieplan' AND th_s.form_id = th.therapieplan_id
            WHERE
               kp.konferenz_id = '{$form_id}'
         ";

         $result = sql_query_array($db, $query);

         if (count($result)) {
            $query = "
               INSERT INTO
                  status_lock_bem ( user_id, bem )
               VALUES (
                  {$user_id}, '{$bem}'
            )";

            mysql_query($query, $db);

            $statusLockBemId = dlookup($db, 'status_lock_bem', 'MAX(status_lock_bem_id)', "user_id = '{$user_id}'");
         }

         foreach ($result as $dataset) {

            $query = "
               UPDATE `status`
               SET
                  form_status = IF(form_status < 3, 3, form_status),
                  status_lock = 1
               WHERE
                  status_id = '{$dataset['kp_status_id']}'
            ";

            mysql_query($query, $db);

            $query = "
               INSERT INTO `status_lock` (status_id, `lock`, status_lock_bem_id, user_id, `time`)
               VALUES ({$dataset['kp_status_id']}, 1, {$statusLockBemId}, {$user_id}, '{$time}')";

            mysql_query($query, $db);


            if (strlen($dataset['th_status_id'])) {
               $query = "
                  UPDATE `status`
                  SET
                     form_status = IF(form_status < 3, 3, form_status),
                     status_lock = 1
                  WHERE
                     status_id = '{$dataset['th_status_id']}'
               ";

               mysql_query($query, $db);

               $query = "
                  INSERT INTO `status_lock` (status_id, `lock`, status_lock_bem_id, user_id, `time`)
                  VALUES ({$dataset['th_status_id']}, 1, {$statusLockBemId}, {$user_id}, '{$time}')";

               mysql_query($query, $db);
            }

            $statusRefresh->setStatusId($dataset['kp_status_id']);
         }

         if (count($statusRefresh->getStatusIds()) > 0) {
            $statusRefresh->refreshStatus();
         }
      }

      if ($no_error) {
         action_cancel( $location );
      }

      break;

   //Geht nur wenn alle konferenz patienten gelöscht wurden
   case 'delete':

      $no_error = action_delete( $smarty, $db, $fields, $table, $form_id, $action );

      if ($no_error){
         deleteForm($db, $smarty, 'konferenz_dokument', 'konferenz', $form_id);
         deleteForm($db, $smarty, 'konferenz_teilnehmer', 'konferenz', $form_id);

         $smarty->config_load(FILE_CONFIG_SERVER,  'upload');

         $config = $smarty->get_config_vars();

         //Verzeichnisse definieren
         $uploadDir    = getUploadDir($smarty, 'upload', false);
         $konferenzDir = $uploadDir['upload'] . $uploadDir['config']['document_dir'] . $uploadDir['config']['konferenz_dir'] . $form_id . '/';

         //Löscht somit auch die KonferenzDokumente
         deltree($konferenzDir);
         action_cancel($location);
      }

      break;

   case 'cancel':

      action_cancel( $location );

      break;
}

?>
