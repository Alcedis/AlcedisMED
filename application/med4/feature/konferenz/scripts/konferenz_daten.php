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

require_once('ie_bugfix.php');

$konferenz_id           = isset($_GET['konferenz_id'])   ? $_GET['konferenz_id'] : '';
$konferenz_patient_id   = isset($_GET['id'])             ? $_GET['id']           : '';
$konferenz_document_id  = isset($_GET['document_id'])    ? $_GET['document_id']  : '';
$view_mode              = isset($_GET['view_mode'])      ? $_GET['view_mode']    : '';

$swf_error_file = $config['swf_error_file'];

if (strlen($view_mode) > 0) {

    switch ($view_mode) {

        case 'presentation':
            if (strlen($konferenz_patient_id) > 0 && strlen($konferenz_id) > 0) {
                $swfFile = convertConferenceProtocoll($smarty, 'swf', $konferenz_patient_id, $konferenz_id);
            } else {
                $swfFile = $swf_error_file;
            }

            break;

        case 'document':
            if (strlen($konferenz_document_id) > 0) {

                $upload = upload::create($smarty)
                    ->setDestinations(array('kd' => array('document', 'konferenz', $konferenz_id)))
                    ->setDestinations(array('d'  => array('doc', 'doc')))
                ;

                $konferenzDokument = reset(sql_query_array($db, "
                    SELECT
                        konferenz_dokument_id,
                        IF (MAX(d.dokument_id) IS NOT NULL,
                            d.dokument,
                            kd.datei
                        ) AS 'name',
                        IF (MAX(d.dokument_id),
                            'd',
                            'kd'
                        ) AS 'type'
                    FROM konferenz_dokument kd
                        LEFT JOIN dokument d ON kd.dokument_id = d.dokument_id
                    WHERE konferenz_dokument_id = '{$konferenz_document_id}'
                    GROUP BY
                        kd.konferenz_dokument_id
                "));

                $fileName = $konferenzDokument['name'];

                if (strlen($fileName) > 0) {
                    $filePath = $upload->getDestination($konferenzDokument['type']) . substr($fileName, 0, -4);

                    $pdfFile = $filePath . '.pdf';
                    $swfFile = $filePath . '.swf';

                    if (str_ends_with($fileName, '.ppt') === false) {
                        if (file_exists($swfFile) === false) {
                            $swfFile = pdf2Swf::create($smarty, $pdfFile)
                                ->callConvert()
                            ;
                        }
                    } else {
                        if (file_exists($swfFile) === true) {
                            unlink($swfFile);
                        }

                        // optimized ppt swf
                        $swfFile = $filePath . '_bmp.swf';

                        if (file_exists($swfFile) === false) {
                            $swfFile = pdf2Swf::create($smarty, $pdfFile, $swfFile)
                                ->callConvert(true)
                            ;
                        }
                    }
                } else {
                    $swfFile = $swf_error_file;
                }
            } else {
                $swfFile = $swf_error_file;
            }

            break;

        default:
            $swfFile = $swf_error_file;

         break;
    }

   if (!isset($swfFile) || !strlen($swfFile) || !is_file($swfFile)) {
      $swfFile = $swf_error_file;
   }

    echo file_get_contents($swfFile);
}

exit;

?>
