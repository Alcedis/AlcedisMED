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
   //Sammelprotokoll
   case 'report':

      if (isset($_REQUEST['report']) === true) {
         $param = explode('-', $_REQUEST['report']);
         $reportType = $param[0];
         $reportParam = $param[1];

         if (strlen($reportParam) > 0 && strlen($reportType) > 0) {

             $type   = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'F';

             if ($reportType == 'protokoll') {
                $upload = getUploadDir($smarty, 'upload', false);

                $outputPath = $upload['upload'] . $upload['config']['document_dir'] . $upload['config']['konferenz_dir'] . $reportParam . '/multi/';

                switch ($type) {
                   case 'F':

                      //Concat Initialize
                      $concatPdf = concatPdf::create()
                         ->setPdfSrcPath($upload['upload'] . $upload['config']['document_dir'] . $upload['config']['doc_dir'])
                         ->setPdfOutputPath($outputPath)
                      ;

                      $konferenzPatienten = sql_query_array($db, "
                          SELECT
                            kp.konferenz_patient_id
                          FROM konferenz_patient kp
                            INNER JOIN patient p ON kp.patient_id = p.patient_id
                          WHERE
                            kp.konferenz_id = '{$reportParam}'
                          GROUP BY
                            kp.konferenz_patient_id
                          ORDER BY
                              p.nachname ASC,
                              p.vorname ASC
                      ");

                      $protocol = protocol::create($db, $smarty, false);

                      foreach ($konferenzPatienten as $kp) {
                            $kpId = $kp['konferenz_patient_id'];

                            if ($protocol->waitForProtocol('konferenz_patient', $kpId) === false) {
                                echo 'protocoll generate error ' . $kpId;
                                exit;
                            }

                            $concatPdf->addPdf("konferenz_patient_{$kpId}/protokoll.pdf");
                      }

                      $concatPdf
                         ->Output('F', 'protokoll.pdf');

                      $output = array(
                         'success'   => 1,
                         'file'      => "index.php?page=list.konferenz&action=report&report=protokoll-{$reportParam}&type=D"
                      );

                      echo json_encode($output);
                      exit;

                      break;

                   case 'D':
                      $datum = dlookup($db, 'konferenz', 'DATE_FORMAT(datum, "%d.%m.%Y")', "konferenz_id = '$reportParam'");

                      $fileName = "sammelprotokoll_konferenz_{$datum}.pdf";

                      download::create($outputPath . 'protokoll.pdf', 'pdf')
                         ->output($fileName);

                      break;
                }
             } else {

               $user = $_SESSION['sess_loginname'];

               switch ($type) {
                  case 'F':
                      empty_user_dir($user);

                      //generiert file und gibt den pfad zurück
                      $report = report::create($db, $smarty, array('konferenzId' => $reportParam))
                         ->setType('phpexcel')
                         ->setName('konferenz')
                         ->setUser($user)
                         ->load()
                       ;

                       $report->saveFile();

                       $fileName = $report->getFileName();

                       $output = array(
                          'success'   => 1,
                          'file'      => "index.php?page=list.konferenz&action=report&report=excel-{$fileName}&type=D"
                       );

                       echo json_encode($output);
                       exit;


                     break;

                  case 'D':

                      download::create(get_user_dir($user) . $reportParam, 'xls')
                         ->output(substr($reportParam, 14));

                     break;
               }
            }
         }
      }

      break;
}

?>
