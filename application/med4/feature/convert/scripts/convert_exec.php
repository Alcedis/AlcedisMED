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

$types   = array('kp' => 'konferenz_patient', 'br' => 'brief', 'zw' => 'zweitmeinung');

$id      = isset($_REQUEST['id']) === true   ? $_REQUEST['id']    : null;
$type    = isset($_REQUEST['type']) === true ? $_REQUEST['type']  : null;

if ($id !== null && $type !== null && array_key_exists($type, $types) === true ) {

   $convertType   = $types[$type];
   $dataset       = sql_query_array($db, "
       SELECT
          x.*,
          p.org_id
       FROM {$convertType} x
           INNER JOIN patient p ON x.patient_id = p.patient_id
       WHERE
          x.{$convertType}_id = '{$id}'
   ");

   //Wenn Datensatz vorhanden
   if (count($dataset) == 1) {
      $dataset = reset($dataset);

      $wip        = strlen($dataset['document_process']) > 0   ? true : false;
      $finished   = strlen($dataset['document_final']) > 0     ? true : false;
      $dirty      = strlen($dataset['document_dirty']) > 0     ? true : false;

      //Nicht weitermachen wenn finished oder in process und nicht dirty
      if (($wip || $finished) && $dirty === false) {
        //DEBUG
         exit;
      }

      if ($dirty === true) {
         protocol::create($db, $smarty, false)
            ->dirtyCheck($convertType, $id)
         ;
      } else {
         mysql_query("UPDATE {$convertType} SET document_process = 1 WHERE {$convertType}_id = '$id'");
         //ALcedis Converter
         $alcConverter = alcXhtmlToPdf::create($db, $smarty)
            ->setContent('protokoll')
            ->setExtension('ergebnis')
            ->setType($convertType)
            ->setParam('id', $id)
            ->setPdfName('protokoll')
            ->createPdf()
         ;

         //If Pdf is created
         if ($alcConverter->getStatus() == 'ok') {
            mysql_query("UPDATE {$convertType} SET document_process = NULL, document_final = 1, document_dirty = NULL WHERE {$convertType}_id = '{$id}'");
         } else {
            mysql_query("UPDATE {$convertType} SET document_process = NULL, document_final = NULL, document_dirty = NULL WHERE {$convertType}_id = '{$id}'");
         }
      }
   }
}

exit;

?>