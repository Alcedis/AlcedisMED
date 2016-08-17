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

switch ($action) {
   case 'allocate':

      $hl7DiagnoseId = isset($_REQUEST['action']['allocate']) == true && is_array($_REQUEST['action']['allocate']) === true
         ? reset(array_keys($_REQUEST['action']['allocate']))
         : null;

      if ($hl7DiagnoseId !== null && strlen($hl7DiagnoseId) > 0) {
         if (isset($_REQUEST['erkrankung']) === true && is_array($_REQUEST['erkrankung']) === true && array_key_exists($hl7DiagnoseId, $_REQUEST['erkrankung']) === true) {

            $writeToDisease = $_REQUEST['erkrankung'][$hl7DiagnoseId];

            if (strlen($writeToDisease) > 0) {

               //Retreive HL7 diagnosis related datasets
               $origin     = reset(sql_query_array($db, "SELECT patient_id, datum FROM hl7_diagnose WHERE hl7_diagnose_id = '{$hl7DiagnoseId}'"));
               $datasets   = sql_query_array($db, "SELECT * FROM hl7_diagnose WHERE patient_id = '{$origin['patient_id']}' AND datum = '{$origin['datum']}'");

               $fields  = array_merge(
                    $widget->loadExtFields('feature/hl7/fields/hl7_diagnose.php'),
                    array(
                        'diagnose_id'   => array('req' => 0, 'size' => '',   'maxlen' => '11' , 'type' => 'hidden',    'ext' => ''),
                        'erkrankung_id' => array('req' => 1, 'size' => '',   'maxlen' => '11' , 'type' => 'hidden',    'ext' => ''),
                        'lokalisation'         => array('req' => 1, 'size' => '',   'maxlen' => ''   , 'type' => 'code_o3',   'ext' => array('type' => 't'), 'default' => '-', 'null' => '-'),
                        'lokalisation_seite'   => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'lookup',    'ext' => array('l_basic' => 'seite'), 'default' => '-', 'null' => '-'),
                        'lokalisation_text'    => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'textarea',  'ext' => '', 'default' => '-', 'null' => '-'),
                        'createuser' => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'hidden',    'ext' => ''),
                        'createtime' => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'hidden',    'ext' => ''),
                        'updateuser' => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'hidden',    'ext' => ''),
                        'updatetime' => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'hidden',    'ext' => '')
                    )
               );

               unset($fields['hl7_diagnose_id']);
               unset($fields['org_id']);

               foreach ($datasets as $dataset) {
                  $hl7DiagnoseId = $dataset['hl7_diagnose_id'];

                  //Check diagnose in disease
                  $diagnoseId = dlookup($db, 'diagnose', 'diagnose_id', "erkrankung_id = '{$writeToDisease}' AND
                                                                         datum = '{$dataset['datum']}' AND
                                                                         diagnose = '{$dataset['diagnose']}' AND
                                                                         diagnose_seite = '{$dataset['diagnose_seite']}' AND
                                                                         lokalisation = '-' AND
                                                                         lokalisation_text = '-'
                  ");

                  if (strlen($diagnoseId) == 0) {
                     $dataset['erkrankung_id'] = $writeToDisease;
                     array2fields($dataset, $fields);

                     execute_insert($smarty, $db, $fields, 'diagnose', 'insert', false, -90);
                  }

                  mysql_query("DELETE FROM hl7_diagnose WHERE hl7_diagnose_id = '{$dataset['hl7_diagnose_id']}'", $db);
               }
            } else {
               $smarty
                  ->assign("error", $config['valid_field'])
                  ->assign("patient_error", array($hl7DiagnoseId))
               ;
            }
         }
      }

      break;

   case 'cancel':

      action_cancel($location);

      break;
}

?>