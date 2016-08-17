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

      if (isset($_REQUEST['report']) === true) {

         $reportData = json_decode(str_replace("'", '"', $_REQUEST['report']), true);

         if (is_array($reportData) === true) {

            $type = isset($reportData['type']) === true  ?  $reportData['type'] : '';
            $id   = isset($reportData['id']) === true    ?  $reportData['id'] : '';

            if (strlen($type) > 0 && strlen($id) > 0) {
               $output = array(
                  'success'   => 1,
                  'file'      => "index.php?page=rec.konferenz_archiv&action=report&type={$type}&id={$id}&dl=true"
               );

               echo json_encode($output);
               exit;
            }
         }
      } elseif (isset($_REQUEST['dl'])) {

         $smarty->config_load('settings/server.conf', 'upload');
         $config     = $smarty->get_config_vars();
         $uploadDir  = get_upload_dir($smarty);

         $type       = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
         $id         = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

         if (strlen($type) > 0 && strlen($id) > 0) {
            switch ($type) {
               case 'kd':

                  $dokumentData = end(sql_query_array($db, "
                     SELECT
                        kd.konferenz_id,
                        IF (d.dokument_id IS NOT NULL,
                            d.dokument,
                            kd.datei
                        ) AS 'datei',
                        IF (d.dokument_id IS NOT NULL,
                            RIGHT(d.dokument, 3),
                            RIGHT(kd.datei, 3)
                        ) AS type,
                        IF (d.dokument_id IS NULL, 1, null) AS 'iscodoc'
                     FROM konferenz_dokument kd
                         LEFT JOIN dokument d ON kd.dokument_id = d.dokument_id

                     WHERE
                         kd.konferenz_dokument_id = '{$id}'
                  "));

                  $datei         = $dokumentData['datei'];
                  $fileType      = $dokumentData['type'];
                  $konferenzId   = $dokumentData['konferenz_id'];
                  $iscodoc       = $dokumentData['iscodoc'];

                  if ($iscodoc == 1) {
                      $outputPath = $uploadDir . $config['document_dir'] . $config['konferenz_dir'] . $konferenzId . '/';
                  } else {
                      $outputPath = $uploadDir . $config['document_dir'] . $config['document_dir'];
                  }

                  download::create($outputPath . $datei, $fileType)
                     ->output(substr($datei, 14));

                  break;

               case 'kp':

                  $outputPath = $uploadDir . $config['document_dir'] . $config['doc_dir'];

                  $patientData = end(sql_query_array($db, "
                     SELECT
                        CONCAT_WS('_', REPLACE(LOWER(p.nachname), ' ', ''), REPLACE(LOWER(p.vorname), ' ',''), kp.art) AS filename
                     FROM konferenz_patient kp
                        LEFT JOIN patient p ON kp.patient_id = p.patient_id
                     WHERE kp.konferenz_patient_id = '$id'
                  "));

                  $fileName = $patientData['filename'];

                  download::create($outputPath . "konferenz_patient_{$id}/protokoll.pdf", 'pdf')
                     ->output("{$fileName}.pdf");

                  break;
            }
         }
      }

      break;
}

?>