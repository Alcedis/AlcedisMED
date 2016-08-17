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

/*
 * Converts pdf to swf
 *
 * @param   Smarty $smarty
 * @param   string $typ
 * @param   int    $konferenz_patient_id
 * @return  string
 */
function convertConferenceProtocoll($smarty, $typ, $conferencePatientId, $conferenceId)
{
   $table   = 'konferenz_patient';
   $swf     = '';

   //Verzeichnisse definieren
   $upload        = getUploadDir($smarty, 'upload', false);
   $documentDir   = $upload['config']['document_dir'];
   $conferenceDir = $upload['config']['konferenz_dir'];

   //Konferenz Patient Dokument prüfen
   $pdfFilePath = $upload['upload'] . $documentDir . $documentDir . "{$table}_{$conferencePatientId}/" . 'protokoll.pdf';

   if (is_file($pdfFilePath) == true) {

      $swfDir        = $upload['upload'] . $documentDir . $conferenceDir . $conferenceId . '/';
      $swfFileName   = 'konferenz_patient_' . $conferencePatientId . '.swf';
      $swfFile       = $swfDir . $swfFileName;

      if (is_dir($swfDir) == false) {
         mkdir($swfDir, 0777, true);
      }

      $pdf2swf = new pdf2Swf($smarty, $pdfFilePath, $swfFile);
      $swf     = $pdf2swf->callConvert();
   }

   return $swf;
}

?>
