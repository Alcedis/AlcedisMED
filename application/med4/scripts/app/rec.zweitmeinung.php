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

$table         = 'zweitmeinung';

$form_id       = isset( $_REQUEST['zweitmeinung_id'] ) ? $_REQUEST['zweitmeinung_id'] : '';
$statusLock    = $statusLocked == true ? true : false;

$location      = get_url('page=view.erkrankung');
$this_location = get_url("page=rec.zweitmeinung&zweitmeinung_id={$form_id}");

//Sonderfall Report View
if ($action == "report" && $statusLock == true) {
  $permission->setForbidden(false);
}

if ($permission->action($action) === true) {
    $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
    require($permission->getActionFilePath());
}

if ($statusLock == true) {
    $smarty->assign('status_locked', true);
}

$button = get_buttons($table, $form_id, $statusLock);

show_record($smarty, $db, $fields, $table, $form_id);

$xhtml = xhtmlManager::create($smarty, $table, $form_id, 'protokoll')
  ->setConvertXhtml()
  ->getXhtml()
;

$documentType = strlen($form_id) ? dlookup($db, 'vorlage_dokument', 'ausgabeformat', "vorlage_dokument_id = '" . reset($fields['vorlage_dokument_id']['value']) . "'") : null;

if (strlen($form_id) > 0) {
    $smarty->assign('convertdoc', 'zw' . $form_id);
}

$smarty
    ->assign('button', $button)
    ->assign('statusLocked', $statusLock)
    ->assign('dokumentTyp', $documentType)
    ->assign('protocolRights', $permission->action('U') === true)
    ->assign('xhtml', $xhtml)
    ->assign('ckEditorJs', DIR_LIB . "/ckeditor/ckeditor.js" )
;

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


function ext_err(validator $valid)
{
   $fields        = $valid->_fields;
   $db            = $valid->_db;
   $erkrankungId  = reset($fields['erkrankung_id']['value']);
   $zweitmeinungId = reset($fields['zweitmeinung_id']['value']);
   $config        = $valid->_smarty->get_config_vars();
}

?>