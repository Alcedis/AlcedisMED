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

$table         = 'konferenz_dokument';

$konferenz_id  = isset($_REQUEST['konferenz_id']) ? $_REQUEST['konferenz_id'] : null;
$dokument_id   = isset($_REQUEST['dokument_id']) ? $_REQUEST['dokument_id'] : null;
$form_id       = isset($_REQUEST['konferenz_dokument_id']) ? $_REQUEST['konferenz_dokument_id'] : '';
$location      = get_url('page=list.konferenz_dokument') . "&konferenz_id={$konferenz_id}";

$upload = upload::create($smarty)
    ->setDestinations(array('datei' => array('document', 'konferenz', $konferenz_id)))
    ->setDestinations(array('dokument' => array('doc', 'doc')))
;

$formDokumentId = dlookup($db, 'konferenz_dokument', 'dokument_id', "konferenz_dokument_id = '{$form_id}'");

$fields['konferenz_patient_id']['ext'] = str_replace('KONFERENZ_ID', $konferenz_id, $fields['konferenz_patient_id']['ext']);

if (strlen($dokument_id) || strlen($formDokumentId) > 0) {

    $dokument_id = strlen($dokument_id) ? $dokument_id : $formDokumentId;

    $dokumentInformation = reset(sql_query_array($db, "
        SELECT
            d.bez,
            o.name AS 'org',
            kp.konferenz_patient_id
        FROM dokument d
            INNER JOIN patient p ON p.patient_id = d.patient_id
            INNER JOIN org o ON o.org_id = p.org_id
            INNER JOIN konferenz_patient kp ON kp.erkrankung_id = d.erkrankung_id AND kp.konferenz_id = '{$konferenz_id}'
        WHERE
            d.dokument_id = '{$dokument_id}'
        GROUP BY
            d.dokument_id
    "));

    $_REQUEST['bez'] = $dokumentInformation['bez'];
    $_REQUEST['konferenz_patient_id'] = $dokumentInformation['konferenz_patient_id'];

    $smarty->assign('info', $dokumentInformation);
}

if ($permission->action($action) === true) {
	require($permission->getActionFilePath());
}

show_record($smarty, $db, $fields, $table, $form_id);

$upload->setFields(array('datei'));
$upload->assignVars($fields);

$button = get_buttons ( $table, $form_id );

$smarty
   ->assign('button',  $button )
   ->assign('back_btn', "page=list.konferenz_dokument&amp;konferenz_id={$konferenz_id}")
;

function upload ($valid)
{
   $fields       = &$valid->_fields;
   $smarty       = &$valid->_smarty;
   $config       = $smarty->get_config_vars();
   $uploadFields = array('datei');
   $upload       = new upload($smarty);

   $upload->setFields($uploadFields);
   $upload->setValidExtensions(array('pdf;ppt;doc;odt'));
   $upload->setMandatory(array($fields['datei']['req']));
   $upload->upload2UserTmp($valid);
   $upload->assignVars($fields);

   $fields['datei']['value'][0] = $upload->getFilename('datei');

   // wenn kein Upload durchgeführt wurde ist hier schluss
   if ($upload->uploadPerformed() === false) {
      return;
   }
}

?>