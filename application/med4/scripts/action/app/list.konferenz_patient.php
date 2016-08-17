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
   case 'report':

     $konferenzPatientId = isset($_REQUEST['report']) === true ? $_REQUEST['report'] : '';

     if (strlen($konferenzPatientId) > 0) {

        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'F';

        switch ($type) {
           case 'F':

              //create new protocol if is dirty
              protocol::create($db, $smarty, false)
                 ->dirtyCheck('konferenz_patient', $konferenzPatientId)
              ;

              $output = array(
                 'success'   => 1,
                 'file'      => "index.php?page=list.konferenz_patient&action=report&report={$konferenzPatientId}&type=D"
              );

              echo json_encode($output);
              exit;

              break;

           case 'D':
              $smarty->config_load('settings/server.conf', 'upload');
              $config     = $smarty->get_config_vars();
              $uploadDir  = get_upload_dir($smarty);

              $outputPath = $uploadDir . $config['document_dir'] . $config['doc_dir'];

              $patientData = end(sql_query_array($db, "
                 SELECT
                    CONCAT_WS('_', REPLACE(LOWER(p.nachname), ' ', ''), REPLACE(LOWER(p.vorname), ' ',''), kp.art) AS filename
                 FROM konferenz_patient kp
                    LEFT JOIN patient p ON kp.patient_id = p.patient_id
                 WHERE kp.konferenz_patient_id = '$konferenzPatientId'
              "));

              $fileName = $patientData['filename'];

              download::create($outputPath . "konferenz_patient_{$konferenzPatientId}/protokoll.pdf", 'pdf')
                 ->output("{$fileName}.pdf");

              break;
        }
     }

      break;

   case 'remove':

     $konferenzPatientId = isset($_REQUEST['konferenz_patient_id']) === true ? $_REQUEST['konferenz_patient_id'] : '';

     if (strlen($konferenzPatientId) > 0) {

         //Konferenz Patient Unsetten
         $dataset = reset(sql_query_array($db, "SELECT * FROM konferenz_patient WHERE konferenz_patient_id = '{$konferenzPatientId}'"));
         $dataset['konferenz_id'] = NULL;

         array2fields($dataset, $fields);

         execute_update($smarty, $db, $fields, 'konferenz_patient', "konferenz_patient_id = '{$konferenzPatientId}'", 'update');

         //Set Dirty State to Protocol
         protocol::create($db, $smarty, false)
            ->makeDirty(protocol::$kp, $konferenzPatientId)
         ;

         //Konferenz Dokumente vom Teilnehmer trennen
         $dokumentFields = $widget->loadExtFieldsOnce('fields/app/konferenz_dokument.php');

         foreach (sql_query_array($db, "SELECT * FROM konferenz_dokument WHERE konferenz_patient_id = '{$konferenzPatientId}'") as $kd) {
             $tmpFields = $dokumentFields;

             $kd['konferenz_patient_id'] = NULL;

             array2fields($kd, $tmpFields);

             execute_update($smarty, $db, $tmpFields, 'konferenz_dokument', "konferenz_dokument_id = '{$kd['konferenz_dokument_id']}'", 'update', "", true);
         }
     }

     action_cancel( $location );

     break;
}

?>