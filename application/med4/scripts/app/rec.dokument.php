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

$table      = 'dokument';
$form_id    = isset($_REQUEST['dokument_id']) ? $_REQUEST['dokument_id'] : '';
$location   = get_url('page=view.erkrankung');
$upload     = new upload($smarty);

$destinations = array(
    'dokument' => array('doc', 'doc')
);

// Nur bei Insert mehrere dokumente ermöglichen
if (strlen($form_id) == 0) {
    for ($i=2;$i<=8;$i++) {
        $fields["dokument{$i}"] = array('req' => 2, 'type' => 'file');
        $fields["bez{$i}"]  = array('req' => 2, 'size' => '', 'maxlen' => '255', 'type' => 'string');
    }
}

//Sonderfall download
if ($action == "file" && $statusLocked == true) {
    $permission->setForbidden(false);
}

$upload->setDestinations($destinations);

if ($permission->action($action) === true) {
   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
   require($permission->getActionFilePath());
}

show_record($smarty, $db, $fields, $table, $form_id);

$upload->setFields(array('dokument'));

$upload->assignVars($fields);

$button = get_buttons($table, $form_id, $statusLocked);

$smarty
   ->assign('filename', substr(reset($fields['dokument']['value']),14))
   ->assign('button', $button)
;

function upload($valid) {
   $fields       = &$valid->_fields;
   $smarty       = &$valid->_smarty;
   $config       = $smarty->get_config_vars();
   $db           = $valid->_db;

   $patientId     = reset($fields['patient_id']['value']);
   $erkrankungId  = reset($fields['erkrankung_id']['value']);
   $datumEn       = todate(reset($fields['datum']['value']), 'en');

   $upload = new upload($smarty);

   $insert = strlen(reset($fields['dokument_id']['value'])) == 0 ? true : false;

   $uploadFields     = array('dokument');
   $validExtension   = array('doc;pdf;ppt;odt');
   $mandatory        = array($fields['dokument']['req']);
   $destinations     = array('dokument' => array('doc', 'doc'));

   if ($insert === true) {
       for ($i=2;$i<=8;$i++) {
           $uploadFields[]   = "dokument{$i}";
           $validExtension[] = reset($validExtension);
           $mandatory[] = 0;
           $destinations["dokument{$i}"] = array('doc', 'doc');
       }
   }

   $upload->setFields($uploadFields);
   $upload->setValidExtensions($validExtension);
   $upload->setMandatory($mandatory);
   $upload->setDestinations($destinations);

   $upload->upload2UserTmp($valid);
   $upload->assignVars($fields);

   //Vermeiden das ukey error geworfen wird
   $originBez   = reset($fields['bez']['value']);
   $originExist = dlookup($db, 'dokument', "COUNT(dokument_id)", "erkrankung_id = '{$erkrankungId}' AND datum = '{$datumEn}' AND bez LIKE('{$originBez}')");

   if ($originExist > 0 && $insert === true) {
      $fields['bez']['value'][0] .= ' (1)';
   }

   if ($insert === true) {
      $fields['dokument']['value'][0]      = $upload->getFilename('dokument');
      $fields['doc_type']['value'][0]  = $upload->getExt('dokument');
   }


   //zusätzliche Bilder im Insert Fall verarbeiten
   if ($upload->uploadPerformed() !== false && $insert === true &&
      isset($valid->err->field) === true && count($valid->err->field) == 0) {
       $widget = $smarty->widget;

       $dokumentFields    = $widget->loadExtFields('fields/app/dokument.php');
       $bem           = reset($fields['bem']['value']);
       $keywords      = reset($fields['keywords']['value']);

       for ($i=2;$i<=8;$i++) {
           $name = "dokument{$i}";

           $fileName = $upload->getFilename($name);

           $bez  = reset($fields["bez{$i}"]['value']);
           $bez  = strlen($bez) == 0 ? $config['noname'] : $bez;

           $exist = dlookup($db, 'dokument', "COUNT(dokument_id)", "erkrankung_id = '{$erkrankungId}' AND datum = '{$datumEn}' AND bez LIKE('{$bez}')");

           if ($exist > 0) {
               $bez .= ' (' . ($bez == $originBez && $originExist > 0 ? $exist + 1 : $exist) . ')';
           }

           // Indikator ob Datei hochgeladen wurde
           if ($fileName !== null) {
               $tmpFields = $dokumentFields;

               $dataset = array(
                  'patient_id' => $patientId,
                  'erkrankung_id' => $erkrankungId,
                  'datum' => $datumEn,
                  'bez' => $bez,
                  'keywords' => $keywords,
                  'dokument' => $fileName,
                  'doc_type' => $upload->getExt($name),
                  'bem' => $bem
               );

               array2fields($dataset, $tmpFields);

               execute_insert($smarty, $db, $tmpFields, 'dokument', 'insert');

               $upload->moveTmp2Folder(array($name => $fileName), false);
           }
       }
   }

   //Fields der zusätzlichen Dokumente entfernen wenn keine Fehler aufgetreten sind
   if ($insert === true && isset($valid->err->field) === true && count($valid->err->field) == 0) {
       for ($i=2;$i<=8;$i++) {
           unset($fields["dokument{$i}"]);
           unset($fields["bez{$i}"]);
       }
   }

}

?>