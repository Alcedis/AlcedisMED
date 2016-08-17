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

$table         = 'brief';
$form_id       = isset( $_REQUEST['brief_id'] ) ? $_REQUEST['brief_id'] : '';
$location      = get_url('page=view.erkrankung');
$this_location = get_url('page=rec.brief&brief_id='.$form_id);

$statusLock    = $statusLocked == true ? true : false;

//Sonderfall Report View
if ($action == "report" && $statusLocked == true) {
   $permission->setForbidden(false);
}

if ($permission->action($action) === true) {
	$location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
	require($permission->getActionFilePath());
}

$button = get_buttons($table, $form_id, $statusLocked);

show_record($smarty, $db, $fields, $table, $form_id);

if (strlen($form_id) || (!strlen($form_id) && strlen($action))) {
	$smarty->assign('weitere_empfaenger', loadDData($db, 'brief_empfaenger', 'empfaenger_id', 'brief_id', $form_id, 'weitere_empfaenger'));
}

$xhtml = xhtmlManager::create($smarty, $table, $form_id, 'protokoll')
   ->setConvertXhtml()
   ->getXhtml()
;

$documentType = strlen($form_id) ? dlookup($db, 'vorlage_dokument', 'ausgabeformat', "vorlage_dokument_id = '" . reset($fields['vorlage_dokument_id']['value']) . "'") : null;

//Fotos
if (appSettings::get('show_pictures') === true) {
   $queryFotos = "SELECT * FROM foto WHERE erkrankung_id = '{$erkrankung_id}'";
   $checkFotos = explode(';', $fields['fotos']['value'][0]);

   $smarty
      ->assign('fotoAktiv',  true)
      ->assign('images',     sql_query_array($db, $queryFotos))
      ->assign('checkFotos', $checkFotos)
   ;
}

if (strlen($form_id) > 0) {
   $smarty->assign('convertdoc', 'br' . $form_id);
}

$smarty
   ->assign('statusLocked', $statusLocked)
   ->assign('button', $button)
   ->assign('protocolRights', $permission->action('U') === true)
   ->assign('dokumentTyp', $documentType)
   ->assign('xhtml', $xhtml)
   ->assign('ckEditorJs', DIR_LIB . "/ckeditor/ckeditor.js" )
;

function ext_err($valid){}
?>