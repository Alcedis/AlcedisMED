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

       $zweitmeinungId = isset($_REQUEST['report']) === true ? $_REQUEST['report'] : '';

       if (strlen($zweitmeinungId) > 0) {

           $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'F';

           switch ($type) {
               case 'F':

                   //create new protocol if is dirty
                   protocol::create($db, $smarty, false)
                       ->dirtyCheck('zweitmeinung', $zweitmeinungId)
                   ;

                   $output = array(
                           'success'   => 1,
                           'file'      => "index.php?page=list.zweitmeinung&action=report&report={$zweitmeinungId}&type=D"
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
                           CONCAT_WS('_', REPLACE(LOWER(p.nachname), ' ', ''), REPLACE(LOWER(p.vorname), ' ',''), 'zweitmeinung', DATE_FORMAT(zw.datenstand_datum, '%Y-%m-%d')) AS filename
                       FROM zweitmeinung zw
                           LEFT JOIN patient p ON zw.patient_id = p.patient_id
                       WHERE
                           zw.zweitmeinung_id = '{$zweitmeinungId}'
                       GROUP BY
                           zw.zweitmeinung_id
                   "));

                   $fileName = $patientData['filename'];

                   download::create($outputPath . "zweitmeinung_{$zweitmeinungId}/protokoll.pdf", 'pdf')
                       ->output("{$fileName}.pdf")
                   ;

                   break;
           }
       }

      break;

}

?>