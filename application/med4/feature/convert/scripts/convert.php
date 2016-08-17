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

$convert = isset($_REQUEST['convert']) === true ? $_REQUEST['convert'] : null;
$status  = array('status' => 'error');

if ($convert !== null && is_string($convert) == true && strlen($convert) >= 3) {
   $types   = array(
      'kp'  => 'konferenz_patient',
      'br'  => 'brief',
      'kd'  => 'konferenz_patient',
      'zw'  => 'zweitmeinung'
   );

   $type    = substr($convert, 0, 2);
   $id      = substr($convert, 2);

   //Checke ob $type valide ist
   if (array_key_exists($type, $types) === true) {
      $convertType   = $types[$type];

      switch ($type) {
         case 'kd':

            $query = "SELECT * FROM {$convertType} WHERE erkrankung_id = '{$id}' AND document_dirty IS NOT NULL";
            $type  = 'kp';

            break;

         default:

            $query = "SELECT * FROM {$convertType} WHERE {$convertType}_id = '{$id}'";

            break;
      }

      $srcDatasets = sql_query_array($db, $query);

      //Wenn mind. ein Datensatz vorhanden
      if (count($srcDatasets) > 0) {
         foreach ($srcDatasets as $dataset) {
            $convertId  = $dataset["{$convertType}_id"];

            $wip        = strlen($dataset['document_process']) > 0   ? true : false;
            $finished   = strlen($dataset['document_final']) > 0     ? true : false;
            $dirty      = strlen($dataset['document_dirty']) > 0     ? true : false;

            //Schon in verarbeitung
            if ($wip === true) {
               $status = array('status' => 'waiting');
            } else {
               //Not Finished OR Dirty
               if ($finished === false || $dirty === true) {
                  if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN'){

                     $med4Exec = $config['med4dir'] . "core\exec.php";
                     $phpDir   = $config['phpdir'];

                     $cmd = "{$phpDir}php.exe {$med4Exec} --feature=convert --page=convert_exec --type=$type --id={$convertId}";

                     pclose(popen("start /B ". $cmd, "r"));
                  } else {

                     $exec = "/usr/bin/php core/exec.php --feature=convert --page=convert_exec --type=$type --id={$convertId} > /dev/null &";

                     //DEBUG
                     exec($exec);
                  }

                  $status = array('status' => 'process');
               } else {
                  //Output alles erledigt //Finished == 1
                  $status = array('status' => 'finished');
               }
            } //end else
         }
      }
   }
}

echo create_json_string($status);

exit;

?>