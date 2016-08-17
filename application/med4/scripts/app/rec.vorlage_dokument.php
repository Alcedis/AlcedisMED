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

$table    = 'vorlage_dokument';
$form_id  = isset( $_REQUEST['vorlage_dokument_id'] ) ? $_REQUEST['vorlage_dokument_id'] : '';
$location = get_url('page=list.vorlage_dokument');
$upload   = new upload($smarty);

$upload->setDestinations(array('package' => array('doc', 'tpl')));

$freigabe = false;
$inaktiv  = false;

if ($form_id) {
   $freigabe   = dlookup($db, 'vorlage_dokument', 'freigabe', "vorlage_dokument_id = '$form_id'") == 1 ? true : false;
   $inaktiv    = dlookup($db, 'vorlage_dokument', 'inaktiv',  "vorlage_dokument_id = '$form_id'") == 1 ? true : false;

   $inactivePermission  = $permission->action('inactive');
   $activePermission    = $permission->action('active');

   $statePermission = false;

   if ($inaktiv == true && $activePermission == true) {
      $statePermission = 'active';
   } elseif($freigabe == true && $inaktiv == false && $inactivePermission == true) {
      $statePermission = 'inactive';
   }

   $smarty
      ->assign('status_locked', $freigabe)
      ->assign('freigabe', $freigabe)
      ->assign('stateButton', $statePermission)
      ->assign('inaktiv', $inaktiv);
}

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

show_record($smarty, $db, $fields, $table, $form_id);

$upload->setFields(array('package'));
$upload->assignVars($fields);

$confirmDial = $rolle_code === 'supervisor' ? false : true;

if ($freigabe == true) {
   $button = 'update.cancel';
} else {
   $button = get_buttons($table, $form_id, null, $confirmDial);
}

//Schritt 3
if ($inaktiv == true) {
   $button = 'cancel';
}

$smarty
   ->assign('button', $button)
   ->assign('back_btn', 'page=list.vorlage_dokument');

function upload($valid)
{
   $fields       = &$valid->_fields;
   $smarty       = &$valid->_smarty;
   $config       = $smarty->get_config_vars();
   $uploadFields = array('package');
   $upload       = new upload($smarty);

   $upload->setFields($uploadFields);
   $upload->setValidExtensions(array('zip'));
   $upload->setMandatory(array(1));
   $upload->upload2UserTmp($valid);
   $upload->assignVars($fields);

   $fields['package']['value'][0] = $upload->getFilename('package');

   // wenn kein Upload durchgeführt wurde ist hier schluss
   if ($upload->uploadPerformed() === false) {
      return;
   }
}

?>