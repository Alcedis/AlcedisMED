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
   case 'insert':
      //Fotos
      if(isset($_REQUEST['konferenz_fotos'])){
         $konferenzFotos = $_REQUEST['konferenz_fotos'];
         $smarty->assign('konferenz_fotos', $konferenzFotos);

         $fotos = array();
         foreach ($konferenzFotos as $fotoId) {
            $fotos[] = $fotoId;
         }

         $fotoArr = implode(';', $fotos);
         $_REQUEST['fotos'] = $fotoArr;

         unset($_REQUEST['konferenz_fotos']);
      }

      $no_error = action_insert($smarty, $db, $fields, $table, $action, '', 'ext_err');

      if ($no_error) {
         $form_id    = dlookup($db, $table, 'MAX(konferenz_patient_id)', "patient_id='$patient_id'");
         $protocol   = protocol::create($db, $smarty)
            ->setType('konferenz_patient', $form_id)
         ;

         if (isset($fields['konferenz_id']['value'][0]) === true && strlen($fields['konferenz_id']['value'][0])) {
            $konferenzId = reset($fields['konferenz_id']['value']);

            $protocol
               ->param('filter', dlookup($db, 'konferenz', 'datum', "konferenz_id = '{$konferenzId}'"))
            ;
         }

         $protocol->generate();

         $location = get_url("page=rec.konferenz_patient&convertdoc=kp{$form_id}&konferenz_patient_id={$form_id}");

         action_cancel($location);
      }

      break;

   case 'update':
      if ($statusLock === false) {
         //Fotos
         if(isset($_REQUEST['konferenz_fotos'])){
            $konferenzFotos = $_REQUEST['konferenz_fotos'];
            $smarty->assign('konferenz_fotos', $konferenzFotos);

            $fotos = array();
            foreach ($konferenzFotos as $fotoId) {
               $fotos[] = $fotoId;
            }

            $fotoArr = implode(';', $fotos);
            $_REQUEST['fotos'] = $fotoArr;

            unset($_REQUEST['konferenz_fotos']);
         }

         $no_error = action_update($smarty, $db, $fields, $table, $form_id, $action, '', 'ext_err');

         if ($no_error) {

            $protocol = protocol::create($db, $smarty)
               ->setType('konferenz_patient', $form_id)
               ->setConvertFilter(array('ergebnis'))
               ->updateTime(false)
            ;

            if (isset($fields['konferenz_id']['value'][0]) === true && strlen($fields['konferenz_id']['value'][0])) {
               $konferenzId = reset($fields['konferenz_id']['value']);

               $protocol
                  ->param('filter', dlookup($db, 'konferenz', 'datum', "konferenz_id = '{$konferenzId}'"))
               ;
            }

            $protocol->generate();

            $location .= "&convertdoc=kp{$form_id}";

            action_cancel($location);
         }
      } else {
         action_cancel( $location );
      }

      break;

   case 'delete':
      if ($statusLock === false) {

         $hl7ExportActive = appSettings::get('interfaces', null, 'hl7_e');

         //HL7 Export
         if ($hl7ExportActive === true) {
             require_once 'feature/hl7/class/export/export.php';
             require_once 'feature/export/base/helper.database.php';

             $exportSettings = array(
                'org_id' => dlookup($db, 'patient', 'org_id', "patient_id = '{$patient_id}'")
             );

             try {
                 HDatabase::LoadExportSettings($db, $exportSettings, 'hl7');
             } catch (EExportException $e) {
                 $hl7ExportActive = false;
             }

             if ($hl7ExportActive === true) {
                 $upload     = getUploadDir($smarty, 'upload', false);
                 $docDir     = $upload['config']['document_dir'];
                 $xhtmlDir   = $upload['config']['xhtml_dir'];
                 $file       = $upload['upload'] . $docDir . $docDir . "konferenz_patient_{$form_id}" . '/protokoll.pdf';
                 $srcFileDir = "{$upload['upload']}{$docDir}{$xhtmlDir}konferenz_patient_{$form_id}/";

                 $documentType = array_key_exists('documentType', $exportSettings) === true ? $exportSettings['documentType'] : null;
                 $exportOnly   = array_key_exists('export_only_after_closure', $exportSettings) === true ? $exportSettings['export_only_after_closure'] : false;

                 $hl7Export = hl7Export::create($db, $smarty)
                     ->setProfile($exportSettings['profile'])
                     ->setParam('type', 'kp')
                     ->setParam('id', $form_id)
                     ->setParam('documentType', $documentType)
                     ->setParam('exportPath',            $exportSettings['path'])
                     ->setParam('receiving_application', $exportSettings['receiving_application'])
                     ->setParam('receiving_facility',    $exportSettings['receiving_facility'])
                     ->setParam('export_only_after_closure', $exportOnly)
                     ->setParam('file', $file)
                     ->setParam('srcFileDir', $srcFileDir)
                     ->buildMessage('delete')
                 ;
             }
         }

         $no_error = action_delete( $smarty, $db, $fields, $table, $form_id, $action);

         if ($no_error) {
            if ($hl7ExportActive === true) {
                $hl7Export->export();
            }

            $upload     = getUploadDir($smarty, 'upload', false);
            $xhtmlDir   = $upload['upload'] . $upload['config']['document_dir'] . $upload['config']['xhtml_dir'] . "{$table}_{$form_id}";
            $docDir     = $upload['upload'] . $upload['config']['document_dir'] . $upload['config']['document_dir']  . "{$table}_{$form_id}";

            deltree($xhtmlDir);
            deltree($docDir);

            action_cancel($location);
         }
      }

      break;

   /**
    * Reportspezifisch
    */

   case 'save_report':
      if ($statusLock === false) {
         if (isset($_POST['editor']) && strlen($_POST['editor'])) {

            $xhtmlManager = xhtmlManager::create($smarty, $table, $form_id, 'protokoll');

            $xhtmlManager
               ->setReconvertXhtml()
               ->setXhtml($_POST['editor'])
               ->saveXhtml();

            $protocol = protocol::create($db, $smarty)
               ->setType('konferenz_patient', $form_id)
               ->setConvertFilter('dummy')
               ->updateTime(false)
               ->generate()
            ;

            $this_location .= "&convertdoc=kp{$form_id}";
         }
      }

      action_cancel( $this_location );

      break;

	case 'gen_report':
      if ($statusLock === false) {
         $no_error = action_update($smarty, $db, $fields, $table, $form_id, 'update', '', 'ext_err');

   	   if ($no_error) {
            $protocol = protocol::create($db, $smarty)
               ->setType('konferenz_patient', $form_id)
            ;

            if (isset($fields['konferenz_id']['value'][0]) === true && strlen($fields['konferenz_id']['value'][0])) {
               $konferenzId = reset($fields['konferenz_id']['value']);

               $protocol
                  ->param('filter', dlookup($db, 'konferenz', 'datum', "konferenz_id = '{$konferenzId}'"))
               ;
            }

            $protocol->generate();

            $this_location .= "&convertdoc=kp{$form_id}";

            action_cancel( $this_location );
   	   }
      }

      break;

   case 'report':
      if (strlen($form_id) > 0) {
         $fileType   = reset(array_keys($_REQUEST['action']['report']));
         $fileName   = "protokoll.{$fileType}";
         $upload     = getUploadDir($smarty, 'upload', false);
         $docDir     = $upload['config']['document_dir'];
         $file       = $upload['upload'] . $docDir . $docDir . "{$table}_{$form_id}" . '/' . $fileName;

         download::create($file, $fileType)
            ->output();
      }

      break;

   case 'cancel':

      action_cancel( $location );

      break;
}

?>
