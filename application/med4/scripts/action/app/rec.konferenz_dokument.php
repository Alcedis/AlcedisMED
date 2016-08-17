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

$smarty->config_load(FILE_CONFIG_SERVER, 'jod');
$config  = $smarty->get_config_vars();

switch ($action)
{
   case 'file':
      $file = reset(array_keys($_REQUEST['action']['file']));

      if ($file == 'dokument_id') {
          $dokument_id = $_REQUEST['dokument_id'];

          $fileName = dlookup($db, 'dokument', 'dokument', "dokument_id = '{$dokument_id}'");

          $file = 'dokument';

      } else {
          $fileName = dlookup($db, $table, $file, "{$table}_id = '$form_id'");
      }

      $filePath = $upload
          ->getDestination($file)
      ;

      download::create($filePath . $fileName, substr($fileName, -3))
         ->output(substr($fileName, 14));

      break;


   case 'insert':
      $no_error = action_insert($smarty, $db, $fields, $table, $action, '', 'upload');

      if ($no_error) {

         $filePath = null;
         $fileName = null;
         $fileType = null;
         $file     = 'datei';

         $dokumentId = reset($fields['dokument_id']['value']);

         if (strlen($dokumentId) > 0) {
             $fileName = dlookup($db, 'dokument', 'dokument', "dokument_id = '{$dokumentId}'");
             $file = 'dokument';

             /*
              * TODO
             //Status lock schreiben
             $statusId = dlookup($db, '`status`', 'status_id', "form = 'dokument' AND form_id = '{$dokumentId}'");

             $bem  = $config['lbl_lock_konferenz'];

             mysql_query("INSERT INTO status_lock_bem (user_id, bem) VALUES ({$user_id}, '{$bem}')", $db);

             $statusLockBemId = dlookup($db, 'status_lock_bem', 'MAX(status_lock_bem_id)', "user_id = '{$user_id}'");

             mysql_query("INSERT INTO `status_lock`
                 (status_id, `lock`, status_lock_bem_id, user_id, `time`)
             VALUES
                 ({$statusId}, 1, {$statusLockBemId}, {$user_id}, NOW())
             ", $db);

             mysql_query("
                 UPDATE `status`
                     SET
                     form_status = IF(form_status < 3, 3, form_status),
                     status_lock = 1
                 WHERE
                 status_id = '{$statusId}'
             ", $db);
             */


         } else {
             $fileName = reset($fields['datei']['value']);

             $upload
                 ->moveTmp2Folder(array('datei' => $fileName))
             ;
         }

         $filePath = $upload->getDestination($file);
         $fileType = end(explode('.', $fileName));

         //Konvertiere ppt to pdf
         if ($fileType !== 'pdf' && $filePath !== null && $fileName !== null && $fileType !== null) {
             jodConverter::create()
                 ->setUrlToJOD($config['converter_url'])
                 ->setFile($filePath, $fileName)
                 ->convert($fileType, 'pdf')
             ;
         }

         action_cancel($location);
      }

      break;


   case 'update':

       action_update($smarty, $db, $fields, $table, $form_id, $action, $location);

      break;

   case 'delete':

      $no_error = action_delete($smarty, $db, $fields, $table, $form_id, $action);

      if ($no_error) {
          $dokumentId = reset($fields['dokument_id']['value']);

          $file = 'datei';

          if (strlen($dokumentId) > 0) {
              $fileName = dlookup($db, 'dokument', 'dokument', "dokument_id = '{$dokumentId}'");
              $file     = 'dokument';
          } else {
              $fileName = reset($fields[$file]['value']);

              $upload
                  ->removeFile($fileName, $file)
              ;
          }

          if (end(explode('.', $fileName)) != 'pdf') {
              $upload->removeFile(substr($fileName, 0, -3) . 'pdf', $file);
          }

          $upload->removeFile(substr($fileName, 0, -3) . 'swf', $file);

          action_cancel($location);
      }

      break;

   case 'cancel':

      $upload->clearUserTMP();
      action_cancel($location);

      break;
}

?>